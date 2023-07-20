<?php 

namespace app\controllers;

use anom\core\Registry;
use anom\core\Render;
use app\controllers\Auth;
use app\extends\App_manager;
use app\models\Content_model;
use app\models\Access_model;
use app\extends\SendMail_service;
use app\extends\Cache_service;

/** Office class
 * 
 * Manages all requests (front/back-office)
 */
class Office {

    ## -------------------------------------------------------------------------
    ## SERVE METHODS (selects)
    ##
    ## -------------------------------------------------------------------------

    ## PERMITION
    ## -------------------------------------------------------------------------

    /** is admin or permited
     * 
     * shortcut method for checking access authorization
     * 
     * returns true if user belogns to the admin group
     * OR has the specified permition/privilege
     * 
     * @param $privilege_id (int)
     * 
     * NOTE:
     * unlike the other authorization methods ...
     * $privilege_id is NOT an array but a single privilege id
     * 
     * @return true|false
     */
    private static function is_admin_or_permited( $privilege_id )
    {
        return (
            Auth::in_admin_group()
            || Auth::hasPermition([ $privilege_id ])
        );
    }




    ## SERVE FILE
    ## -------------------------------------------------------------------------

    /** serve file by file_path
     * (request is valid only for admin users)
     * 
     * @param $file_path (string): relative file path
     * GET @param type (string) : media-type of file
     */
    public static function serve_file($file_path)
    {
        $file = self::get_file_attributes($file_path);

        // if user is authorized
        if (self::is_admin_or_permited($file['privilege_id'])) {

            $media_type = Registry::get('REQUEST')->GET['type'];    // get media-type
            $real_path = MEDIA_STORAGE_ROOT . $file_path;           // construct real path

            if (!file_exists($real_path)) {
                Render::view('error/404');

            } else {
                Render::file($real_path, $media_type);
            }

        } else {
            Render::view('error/404', [
                'error_code' => 403,
                'moto' => 'Forbidden',
                'message' => ''
            ]);
        }
    }


    /** get_file_attributes
     * 
     * returns attributes of a file
     * (medias are proxied for speed optimization)
     * 
     * @param $path (string) : file path
     * @return $file attributes --or-- false
     */
    private static function get_file_attributes($path)
    {
        $medias = Cache_service::files_attributes();

        foreach($medias as $key => $medi) {

            if ($medi['path'] == $path) {

                return $medi;
            }
        }

        return false;
    }




    ## PETITION FORMS
    ## secretarial support / serve forms for teachers' requests and applications
    ## -------------------------------------------------------------------------

    /** request any (empty/new) petition form
     * 
     * The method acts as an authorization checker and internal router
     * to actual petition form-templates
     * 
     * @param $petition_tag (string): petition type ex. [common|penalty]
     */
    public static function request_petition($petition_tag)
    {
        // get current user
        $manager = new App_manager();
        if (!$manager->hasUserToken()) {    // if user is not connected
            Render::view('error/404', [
                'error_code' => 403,        // serve forbidden
                'moto' => 'Forbidden',
                'message' => 'Για να έχετε πρόσβαση, θα πρέπει πρώτα να συνδεθείτε'
            ]);
            die();      // then die();

        } // else continue...

        $token = $manager->getUserToken();      // from token
        $user = $token->getUser();          // create user
        $id = $user->getID();       // keep id user

        switch ($petition_tag) {    // route to specific type of petition

            case 'application':
                self::render_application_form(['user' => $user]);
                break;

            case 'penalty':
                self::render_penalty_form(['user' => $user]);
                break;

            default:        // if not a known petition type
                Render::view('error/404', [     // then serve not-found
                    'message' => 'Δεν βρέθηκε το είδος της αίτησης ή του εγγράφου που ζητήσατε.'
                ]);
        }
    }


    /** render an (empty/new) application form
     * 
     * @param array $opts : user identity ([user_id => id])
     */
    private static function render_application_form($opts)
    {
        // #1: user-id ---------------------------------------------------------
        $user_id = $opts['user']->getID();
        
        // #2: form template ---------------------------------------------------
        $form_setup = json_decode(json_encode(APPLICATION_FORM, JSON_UNESCAPED_UNICODE));

        // #3: inject default values -------------------------------------------

        $userData = Auth::user_data();

            // #3.1: inject user data
        $form_setup->defaults = json_decode( json_encode(
            $userData, JSON_UNESCAPED_UNICODE
        ));

            // #3.2 inject `sector` and `position` <option>s
        for($i=0; $i < sizeof($form_setup->form) ; $i++) {      // get key-IDs
            if (isset($form_setup->form[$i]->name)) {
                if ($form_setup->form[$i]->name == 'sector') { $keySector = $i; }
                if ($form_setup->form[$i]->name == 'position') { $keyPosition = $i; }
            }
        }
        $form_setup->form[$keySector]->options = TEACHER_SECTORS;
        $form_setup->form[$keyPosition]->options = ($userData['prefix'] == 'η')
            ? GENDERED_POSITIONS['she']
            : GENDERED_POSITIONS['he'];

        // #4: get Form's HTML and Javascript ----------------------------------
        $form = JsonToForm::json_form($form_setup, [
            ['name' => 'user_id', 'value' => $user_id]      // pass user identity
        ]);

        // #5: create ticket; then render the view -----------------------------
        $ticket = self::create_ticket();      // save ticket
        Render::view('templates/application', [
            'form' => $form,
            'applier' => (($userData['prefix'] == 'η') ? 'Η Αιτούσα' : 'Ο Αιτών'),
            'ticket' => $ticket
        ]);
    }


    /**
     * render an (empty/new) penalty form
     * 
     * @param array $opts : user identity ([user_id => id])
     */
    private static function render_penalty_form($opts)
    {
        // #1: user-id ---------------------------------------------------------
        $user_id = $opts['user']->getID();  

        // #2: form template ---------------------------------------------------
        $form_setup = json_decode(json_encode(PENALTY_FORM, JSON_UNESCAPED_UNICODE));

        // #3: prepare values to inject; then inject ---------------------------

            // #3.1: get all teachers
        $teachers_array = Registry::use('database')->runQuery(
            "SELECT concat(last_name, ' ', first_name) as TeacherName
            FROM user WHERE deprecated IS NULL ORDER BY last_name, first_name", []
        );
        $teachers = [];     // constuct teacher names as a simple array
        foreach($teachers_array as $key => $person) {
            $teachers[] = $person['TeacherName'];
        }

            // #3.2: get $key of `rapporteur`, `president` and `members` <select>s
        for($i=0; $i < sizeof($form_setup->form) ; $i++) {
            if (isset($form_setup->form[$i]->name)) {
                if ($form_setup->form[$i]->name == 'rapporteur') { $keyRapporteur = $i; }
                if ($form_setup->form[$i]->name == 'president') { $keyPresident = $i; }
                if ($form_setup->form[$i]->name == 'members') { $keyMembers = $i; }
            }
        }

            // #3.3: inject <option>s for `rapporteur`, `president` and `members`
        $form_setup->form[$keyRapporteur]->options = $teachers;
        $form_setup->form[$keyPresident]->options = $teachers;
        $form_setup->form[$keyMembers]->options = $teachers;

        // #4: get Form's HTML and Javascript ----------------------------------
        $form = JsonToForm::json_form($form_setup, [
            ['name' => 'user_id', 'value' => $user_id]       // pass user identity
        ]);

        // #5: create ticket; then render the view -----------------------------
        $ticket = self::create_ticket();      // save ticket
        Render::view('templates/penalty', [
            'form' => $form,
            'ticket' => $ticket
        ]);

    }




    ## PETITION SUBMIT
    ## handle submits of forms
    ## -------------------------------------------------------------------------

    /** add_petition
     * 
     * handle an add petition request (petition form is submited)
     * 
     * @param void; all params are readed from POST, Auth and SESSION
     * @return int new petition-id
     */
    public static function add_petition()
    {
        $post = Registry::get('REQUEST')->POST;
                
        if (self::remove_ticket($post['ticket'])) {     // check ticket + remove

            $manager = new App_manager();       // use APP Manager
                // to get the real author (user_id) of the petition

            $new_id = Content_model::add_petition([
                'user_id' => $manager->getUserToken()->getUser()->getID(),
                'type_id' => $post['type_id'],
                'subject' => $post['subject'],
                'signature' => $post['ticket'],
                'form_structure' => serialize($post)
            ]);

            Render::json([
                'success' => true,
                'id' => $new_id
            ]);

        } else {
            Render::json(['success' => false, 'error' => TICKET_EXPIRED]);
        }
    }




    ## LIST PETITIONS
    ## -------------------------------------------------------------------------

    public static function user_petitions()
    {
        $manager = new App_manager();       // use APP Manager
        // to get the requester's user_id

        $list = Content_model::user_petitions(
            $manager->getUserToken()->getUser()->getID()    // user-id
        );

        Render::json([
            'success' => true,
            'data' => $list
        ]);
    }




    ## SEND INVITATION(s)
    ## -------------------------------------------------------------------------

    public static function send_invitation()
    {
        if (Auth::in_admin_group()) {

            $now = time();      // keep `now` and `expiration` timestamps
            $expiration = $now + ( 3600 * 72 );

            // prepare the prepare statement
            $handler = [];
            $binds = [ ':now' => $now ];     // sure value to bind

            foreach(Registry::get('REQUEST')->POST['ids'] as $key => $id) {
                $handler[] = ':id'. $key;
                $binds[':id'. $key] = $id;
            }

            $all_handlers = implode(', ', $handler);

            $recipients = Registry::use('database')->runQuery(
                "SELECT * FROM user
                WHERE id IN ( $all_handlers ) AND (
                    otp_expiration < :now OR otp_expiration IS NULL
                ) AND deprecated IS NULL",
                $binds
            );

            $sent = [];
            $failed = [];

            foreach($recipients as $key => $rec) {
                $invitation = md5(
                    $rec['first_name']
                    . $rec['last_name']
                    . $rec['email']
                    . rand(0, 999999)
                );

                $gone = SendMail_service::send_invitation([
                    'email' => $rec['email'],
                    'name'  => $rec['last_name'] .' '. $rec['first_name'],
                    'code'  =>  $invitation
                ]);

                if ($gone === true) {
                    // update user record with invitation;
                    Access_model::invite_user($rec['id'], $invitation, $expiration);

                    $sent[] = $rec['last_name']     // keep data for echoing
                        ." ". $rec['first_name']
                        ." (". $rec['id'] .")"
                        .": ". $invitation;

                } else {
                    $failed[] = [
                        'rec' => $rec['last_name'] ." ". $rec['first_name']
                            ." (". $rec['id'] .")",
                        'mdg' => $gone
                    ];
                }
            }

            return Render::json([
                'success' => (empty($failed)),
                'sent' => implode(",\n\n", $sent),
                'failed' => $failed
            ]);

        }
    }

    

    public static function petition_copy($sign)
    {
        if (Auth::in_admin_group()) {
            $petition = Content_model::get_petition(['signature' => $sign]);

        } else {
            $manager = new App_manager();
            $petition = Content_model::get_petition([
                'signature' => $sign,
                'user_id' => $manager->getUserToken()->getUser()->getID()
            ]);
        }
        if ($petition === false)    Render::view('error/404');
        else {
            Render::view( 'templates/create_pdf',['petition' => $petition]);
        }
    }




    ## TICKET METHODS (create, remove)
    ## tickets eliminate CSRF attacks
    ## -------------------------------------------------------------------------

    /** create_ticket
     * 
     * creates a tickef and saves it into session
     * 
     * @return string new ticket (MD5)
     */
    private static function create_ticket()
    {
        // create ticket
        $tick = md5( time() . json_encode(Auth::user_data()) . rand(1,65536) );
        // then save to session
        if (isset($_SESSION['tickets'])) {
            $tickets = explode(',', $_SESSION['tickets']);

            if (count($tickets) > 99) {     // if more than 99 tickets
                array_shift($tickets);      // remove the older
            }
            $tickets[] = $tick;     // add new ticket to the tickets list
            $_SESSION['tickets'] = implode(',', $tickets);

        } else {
            $_SESSION['tickets'] = $tick;
        }

        return $tick;               // return the xreated ticket
    }

    /** remove_ticker
     * 
     * removes a ticket and return true;
     * NOTE: if ticket not exists return false;
     * 
     * @param string $t : ticket (MD5)
     * @return boolean
     */
    private static function remove_ticket($t)
    {
        if (isset($_SESSION['tickets'])) {
            $tickets = explode(',', $_SESSION['tickets']);
            if (($key = array_search($t, $tickets)) !== false) {
                unset($tickets[$key]);
                $_SESSION['tickets'] = implode(',', $tickets);
                return true;
            }
        }
        return false;
    }



    ## ADMIN METHODS (insert, updated etc.)
    ## -------------------------------------------------------------------------

    /** set_protocol
     * ---
     */
    public static function set_protocol()
    {
        if (Auth::in_admin_group()) {
            $post = Registry::get('REQUEST')->POST;
            Content_model::set_protocol( $post['id'], $post['protocol'] );
        }
        Render::json(['success' => true]);
    }

    
    /** files
     * echo all files
     * 
     * @return (array)
     */
    public static function files()
    {
        return  Cache_service::files_attributes();
    }

    /** upload_file
     * upload the file to the file system
     * 
     * the method reads the POST and FILES array
     * to retrieve all needed parametres
     *
     * FILES @param file
     * POST @param folder : petition's ID or somthing random
     * SESSION @param user_id
     */
    public static function upload_file()
    {
        $request = Registry::get('REQUEST');

        $uploaded = self::upload_to_fs();    // upload file to file-system

        if ($uploaded['success']) {

            $media_id = self::define_media([    // define media in database; get id
                'title' => $request->POST['title'],
                'type' => $uploaded['type'],
                'path' => $uploaded['path']
            ]);

            Render::json([      // render results as json
                'success' => true,
                'id' => $media_id,
                'title' => $request->POST['title'],
                'path' => $uploaded['path'],
                'type' => $uploaded['type']
            ]);

        } else {
            Render::json(['success' => false ]);
        }
    }

    /** upload to fs
     * upload file to File-System
     * 
     * POST @param folder
     * FILES @param file
     */
    private static function upload_to_fs()
    {
        $post = Registry::get('REQUEST')->POST;
        $files = Registry::get('REQUEST')->FILES;


        // Checks before uploading the file
        ////////////////////////////////////////////////////////////////////////

        // ** 1: file is upladed to temporary folder ---------------------------
        if (! is_uploaded_file($files['file']['tmp_name'])) {
            return ['success' => false];       // bye!
        }

        // ** 2: File belongs to the allowed MIME types ------------------------
        $allowed_file_types = [
                // pdf
            'application/pdf',
                // images
            'image/png', 'image/jpeg',
                // word
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                // excel
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                // rar
            'application/vnd.rar', 'application/x-rar-compressed', 'application/octet-stream',
                // zip
            'application/zip', 'application/x-zip-compressed', 'multipart/x-zip'
                // 'application/octet-stream' refers to zip; also to rar (no-need to re-include)
        ];
        // Recomended MIME type checking via mime_content_type():
        $mime_type = mime_content_type($files['file']['tmp_name']);     
        if (! in_array($mime_type, $allowed_file_types)) {      // File type NOT allowed ...
            return ['success' => false];       // bye!
        }
     
        $file_name = $files['file']['name'];
        $file_type = $files['file']['type'];    // do not take it for granted
        $file_size = $files['file']['size'];
        $file_tmp  = $files['file']['tmp_name'];

        $bare_name = pathinfo($file_name, PATHINFO_FILENAME);
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        

        // ** 3: filename or size checks may be added --------------------------
        if ($file_name == "") {
            return ['success' => false];       // bye!
        }


        // READY to finaly save/upload the file to CDN /////////////////////////

        $folder = MEDIA_STORAGE_ROOT . $post['folder'];
        if (!file_exists($folder)) {    // create folder if not exists
            mkdir($folder, 0757, true);
        }

        // print_r([ 
        //     'dir' => $folder,
        //     'file' => $bare_name,
        //     'ext' => $file_ext,
        //     'type' => $file_type
        // ]); die();

        $relative_filename = $post['folder']
            .'/'
            . strtolower(self::clear_file_name($bare_name) .'.'. $file_ext);
        $store_filename = MEDIA_STORAGE_ROOT . $relative_filename;

        if (move_uploaded_file($files["file"]["tmp_name"], $store_filename)) {
            return [
                'success' => true,
                'path' => $relative_filename,
                'type' => $mime_type
            ];

        } else { return ['success' => false]; }

    }

    /** clear_file_name
     * replace greek characters and strip symbols
     */
    private static function clear_file_name($str)
    {
        $el = mb_split( "ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαβγδεζηθικλμνξοπρστυφχψωάέήίόύώϊϋς ", "");
        $en = str_split("ABGDEZHUIKLMNJOPRSTYFXCVabgdezhuiklmnjoprstyfxcvaehioyviys-");
        $strip = str_split("!@#$%^&*()+~`[]{};'/<>?=\"");

        return str_replace($strip, '', str_replace($el, $en, $str));
    }

    
    /** define_media
     * 
     * create a record in media table
     * 
     * @param $data (array): [title => , path => , type => mime-type]
     * @return id (int): id of created media record
     */
    private static function define_media($data)
    {
        $request = Registry::get('REQUEST');

        $media_id = Registry::use('database')->query(
            "INSERT INTO media (label, `type`, `path`)
            VALUES (:label, :mimetype, :filepath)",
            [
                'label' => $data['title'],
                'mimetype' => $data['type'],
                'filepath' => $data['path']
            ]
        )->lastInsertID();
		return $media_id;
    }

    /** create media for petition
     * 
     * links petition to each media-file of the media `id`s array
     * 
     * @param $media (array): a list of media-file `id`s
     * @param $petition_id (int)
     */
    private static function create_medias_for_petition($medias, $petition_id)
    {
        foreach($medias as $key => $medi) {
            self::link_media_to_petition($medi, $petition_id);   // link to petition
        }
        return true;
    }

    /** link one media-file to a specific post
     *
     * NOTE:
     * the method does not check if media is linked already
     * so be sure that the pair of (media_id,post_id) not exist
     * 
     * @param $media_id (int)
     * @param $petition_id (int)
     */
    private static function link_media_to_petition( $media_id, $petition_id )
    {
        Registry::use('database')->runQuery(
            "INSERT INTO petition_media (petition_id, media_id)
            VALUES (:petition, :media)",
            [
                'petition' => $petition_id,
                'media' => $media_id,
            ]
        );
        return true;
    }

   
}
