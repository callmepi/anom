<?php
namespace app\controllers;

use anom\core\Registry;
use anom\core\Render;

// user classes and models
use app\controllers\JsonToForm;
use app\extends\App_user;
use app\extends\App_manager;
use app\models\Access_model;
use app\extends\SendMail_service;



/** class Auth
 * 
 * handles user's Authentication and Authorizarion
 * 
 */
class Auth {

    ## handle user's record / token / session
    ## -------------------------------------------------------------------------
    
    /** User from Record
     * 
     * create and return a user based on the record of properties
     * 
     * @param array $record
     * @return AppUser
     */
    private static function user_fromRecord($record)
    {
        // create a user object
        $user = (new App_user())
            ->setID($record['id'])
            ->setSex( ($record['prefix'] == 'η') ? 2 : 1 )
            ->setUserName($record['email'])
            ->setName($record['first_name'] .' '. $record['last_name'])
            ->setPassword($record['password'])
            ->setRoles([ $record['role_id'] ])      // roles is an array
            ->setEnabled( ($record['active'] == 0) ? false : true )
            ->set('prefix', $record['prefix'])
            ->set('first_name', $record['first_name'])
            ->set('last_name', $record['last_name'])
            ->set('father_name', $record['father_name'])
            ->set('phone', $record['phone'])
            ->set('email', $record['email'])
            ->set('sector', $record['sector'])
            ->set('position', $record['position'])
            ->set('registration_number', $record['registration_number'])
            ->set('belonging_school', $record['belonging_school']);
        return $user;
    }


    /** setSessionToken_forUser
     * (RE-)set Session-Token for specified user object
     * 
     * @param AppUser $user
     */
    private static function setSessionToken_forUser($user)
    {       
        $userManager = new App_manager();

        // regeneration session ID (prevent session fixation)
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage before continue
        session_start();
        session_regenerate_id();
        
        // set cookie for connected user
        setcookie(
            CONNECTION_COOKIE,
            rawurlencode(json_encode(
                [ 'rid' => $user->getRoles()[0], 'name' => $user->getName() ],
                JSON_UNESCAPED_UNICODE
            )),
            [
                'expires' =>time()+60*60*10,    // 10 hours
                'path' => '/',
                // 'domain' => COOKIE_DOMAIN,
                'secure' => COOKIE_SECURE,
                'samesite' => COOKIE_SAMESITE
            ]
        );

        // login OK, set Token in session
        $userManager->createUserToken($user);

        return true;
    }


    /** update session token
     * ... for user by user-id
     * 
     * This method perfomes
     * a new READ User's data from DataBase
     * 
     * @param int $uid: user-id
     */
    private static function reset_user_token($uid)
    {
        $rec = Access_model::getUser_byID($uid);
        if ($rec !== false) {
            $user = self::user_fromRecord($rec);
            self::setSessionToken_forUser($user);
        }
        return true;
    }


    ## handle comon ajax requests
    ## (login, activate. update etc )
    ## -------------------------------------------------------------------------

    /** AJAX POST: login
     * 
     * checks visitor's credentials;
     * if valid, authenticates user
     * 
     */
    public static function login()
    {
        $req = Registry::get('REQUEST');

        // get the record of the target user
        $record = Access_model::checkUser($req->POST['email']);
       
        // if no user exists, return false
        if ($record === false)     return false;
        
        // user is valid; check user password
        $user = self::user_fromRecord($record);     // create User object
        $userManager = new App_manager();           // let Manager to check       
        if ($userManager->isPasswordValid($user, $req->POST['password'])) {

            self::setSessionToken_forUser($user);   // re-set Session Token

            return [
                'success' => true,
                'goto' => '/panel'
            ];
    
        } else {
            return false;
        }
    }


    /** AJAX POST: activate
     * resolves call POST:/account/activate
     * 
     * @param void : all parametres passed via request->POST
     */
    public static function activate()
    {
        $req = Registry::get('REQUEST');
        // print_r($req->POST); die();

        // create a salted password hash
        $userManager = new App_manager();
        $password = $userManager->cryptPassword($req->POST['password']);

        // acivate target user and set password
        $check = Access_model::activate_set_password($req->POST, $password);
        // TODO: Cache_service::update_teachers();

        if ($check != 0) {
            Render::json([
                'title' => ACCOUNT_ACTIVATED_TITLE,
                'message' => ACCOUNT_ACTIVATED_MESSAGE . '<br>(msg code: '. $check .')'
            ]);

        } else  {
            Render::json([
                'title' => NOT_VALID_ACTIVATION_TITLE,
                'message' => NOT_VALID_ACTIVATION_MESSAGE
            ]);
        }

    }


    /** AJAX POST: update_user
     * 
     * resolves call POST:/user/update/properties;
     * updates user's proiperties with POSTed data
     * 
     * @param void : all parametres passed via request->POST
     */
    public static function update_user()
    {
        $req = Registry::get('REQUEST');
        $manager = new App_manager();
        $uid = $manager->getUserToken()->getUser()->getID();

        // ask model to update user; get user-id from user-manager
        $check = Access_model::update_user(
            $req->POST,
            $uid
        );
        // TODO: Cache_service::update_teachers();

        // user properties changed, so update user's session token
        self::reset_user_token($uid);

        Render::json([
            'title' => ACCOUNT_UPDATED_TITLE,
            'message' => ACCOUNT_UPDATED_MESSAGE . '<br>(msg code: '. $check .')'
        ]);

    }


    /** AJAX POST: update_password
     * 
     * resolves call POST:/user/update/password;
     * updates user's proiperties with POSTed data
     * 
     * @param void : all parametres passed via request->POST
     */
    public static function update_password()
    {
        $req = Registry::get('REQUEST');
        $manager = new App_manager();
        $user = $manager->getUserToken()->getUser();

        $record = Access_model::checkUser($user->get('email'));

        // confirm old-password
        if ($manager->isPasswordValid($user, $req->POST['oldpass'])) {

            $check = Access_model::update_password(     // then update pass
                $user->getID(),
                $manager->cryptPassword($req->POST['password'])
            );

            // user properties changed, so update user's session token
            self::reset_user_token($user->getID());

            Render::json([
                'title' => ACCOUNT_UPDATED_TITLE,
                'message' => ACCOUNT_UPDATED_MESSAGE . ' (msg code: '. $check .')'
            ]);

        } else {
            Render::json([
                'title' => 'Error',
                'message' => 'Λάθος Password<br>(msg code: 2)<br><br>'. RETRY_SET_PASSWORD
            ]);
        }
    }


    ## serve user management forms
    ## -------------------------------------------------------------------------

    /** SERVE FORM: invitation
     * 
     * setups and renders the form for a certain invitation
     * 
     * NOTE:
     * after form is submited, client shall call Auth::activate()
     * 
     * @param $id (string|MD5) : invitation code
     * 
     */
    public static function invitation($id)
    {
        // get user from invitation number
        $user_array = Access_model::getUserByInvitation($id);

        if ($user_array == false) {     // no invitation ? serve error then die;
            Render::view('error/404', ['moto' => 'Δεν βρέθηκε η πρόσκληση']);
            die();
        }
        $user = json_decode(json_encode($user_array, JSON_UNESCAPED_UNICODE));

        // format form_setup to a json array
        $form_setup = json_decode(json_encode(REGISTRATION_FORM, JSON_UNESCAPED_UNICODE));

        // get $key of position item inside the form_setup->form array
        for($i=0; $i < sizeof($form_setup->form) ; $i++) {
            if ($form_setup->form[$i]->name == 'position') { $key = $i; }
        }

        // prepare gendered options to position field
        $positions = ($user->prefix != 'η')
            ? GENDERED_POSITIONS['he']
            : GENDERED_POSITIONS['she'];
            
        $gendered_position = json_decode( json_encode( $positions, JSON_UNESCAPED_UNICODE ));

        // attach gendered options
        $form_setup->form[$key]->options = $gendered_position;

        // attach default values 
        $form_setup->defaults = $user;

        // get Form's HTML and Jsvascript
        $form = JsonToForm::json_form($form_setup, [
            // pass invitation identity for security
            ['name' => 'id', 'value' => $user->id],
            ['name' => 'invitation', 'value' => $user->invitation]
        
        ]); // print_r($form_setup); die();

        Render::view('user/invitation', [
            'form' => $form,
            'invitation_number' => $user->invitation
        ]);
    }


    /** SERVE FORM: update_form
     * 
     * renders a form with all user's properties
     * 
     * NOTE:
     * after form is submited, client shall call Auth::update_user()
     * 
     */
    public static function update_form()
    {
        $user = json_decode(json_encode(self::user_data(), JSON_UNESCAPED_UNICODE));

        // format form_setup to a json array
        $form_setup = json_decode(json_encode(USER_PROPERTIES_FORM, JSON_UNESCAPED_UNICODE));

        // get $key of position item inside the form_setup->form array
        for( $i=0; $i < sizeof($form_setup->form) ; $i++ ) {
            if ($form_setup->form[$i]->name == 'position') { $key = $i; }
        }

        // prepare gendered options to position field
        $positions = ($user->prefix != 'η')
            ? GENDERED_POSITIONS['he']
            : GENDERED_POSITIONS['she'];           
        $gendered_position = json_decode( json_encode( $positions, JSON_UNESCAPED_UNICODE ));
        $form_setup->form[$key]->options = $gendered_position;

        // attach default values 
        $form_setup->defaults = $user;

        // get Form's HTML and Jsvascript
        $form = JsonToForm::json_form($form_setup, [
            // pass invitation identity for security
            ['name' => 'id', 'value' => $user->id]
        ]); // print_r($form_setup); die();

        Render::view('user/update_properties', [
            'form' => $form
        ]);
    }


    /** SERVE FORM: password_form
     * 
     * renders a form for user to change password
     * 
     * NOTE:
     * after form is submited, client shall call Auth::update_user()
     * 
     */
    public static function password_form()
    {
        $user = json_decode(json_encode(self::user_data(), JSON_UNESCAPED_UNICODE));

        // format form_setup to a json array
        $form_setup = json_decode(json_encode(SET_PASSWORD_FORM, JSON_UNESCAPED_UNICODE));

        // get Form's HTML and Jsvascript
        $form = JsonToForm::json_form($form_setup, [
            // pass invitation identity for security
            ['name' => 'id', 'value' => $user->id]
        ]); // print_r($form_setup); die();

        Render::view('user/update_password', [
            'form' => $form
        ]);
    }


    ## suplamentary methods
    ## -------------------------------------------------------------------------

    /** is_connected
     * checks if the user is connected
     * 
     * @return true|false
     */
    public static function is_connected()
    {
        $manager = new App_manager();
        if ($manager->hasUserToken()) {

            return true;

        } else {
            // user is not connected;
            return false;
        }
    }


    /** user_data 
     * returns user's public data
     */
    public static function user_data()
    {
        $manager = new App_manager();
        if ($manager->hasUserToken()) {

            // user is connected;
            $token = $manager->getUserToken();
            $user = $token->getUser();

            return [
                'id' => $user->getID(),
                'name' => $user->getName(),
                'roles' => $user->getRoles(),

                'sex' => $user->getSex(),
                'active' => $user->isEnabled(),
                'prefix' => $user->get('prefix'),
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
                'father_name' => $user->get('father_name'),
                'phone' => $user->get('phone'),
                'email' => $user->get('email'),
                'sector' => $user->get('sector'),
                'position' => $user->get('position'),
                'registration_number' => $user->get('registration_number'),
                'belonging_school' => $user->get('belonging_school')

                // 'token' => $token->serialize()
            ];

        } else {
            // user is not connected;
            return [ 'user' => 0 ];
        }
    }


    /** logout
     * 
     * performs a secure logout;
     * regenerates session; deletes cookies;
     */
    public static function logout()
    {
        $userManager = new App_manager();
        $userManager->logout();

        // regeneration session ID (prevent session fixation)
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage before continue
        session_start();
        session_regenerate_id();

        // remove user-conected cookie
        if (isset($_COOKIE[CONNECTION_COOKIE])) {
            unset($_COOKIE[CONNECTION_COOKIE]); 
            setcookie(CONNECTION_COOKIE, '', -1, '/'); 
            return true;
            
        } else {
            return false;
        }
    }


    /** TODO:
     * 
     */
    public static function forgot_password()
    {
    }


    /** TODO:
     * 
     */
    public static function validate_otp()
    {
    }



    /** TODO:
     * 
     */
    public static function reset_password()
    {
    }


    /** allowRoles
     * 
     * method filters access for certain roles
     * if user is not grented acces, a forbiden message is sent and app ends._
     * otherwise the method returns true (app will continue)
     * 
     * @param $allowed (array) : array of allowed roles
     * @return true or die();
     */
    public static function allowRoles($allowed)
    {
        $manager = new App_manager();
        if ($manager->isGranted($allowed)) {    // if valid, return true (continue)
            return true;

        } else {    // no user, no access; die._
            Render::view('error/general', [
                'title' => 'Forbidden',
                'message' => 'Access is forbidden'
            ]);
            die();
            return false;       // this line will never run
        }
    }


    /** hasValidRole
     * like allowRoles() but dowes not stop execution
     * 
     * @return true|false
     */
    public static function hasValidRole($allowed)
    {
        $manager = new App_manager();
        return ($manager->isGranted($allowed));
    }


    public static function in_admin_group()
    {
        if (!self::is_connected())  return false;
        
        $manager = new App_manager();
        return ($manager->isGranted([1, 2, 3]));
    }


    
}
