<?php
/** Rendering System
 * 
 * this is the View part of the anom MVC framework
 ***/
namespace anom\core;

class Render {

    /** load template
     * --- 
     * TODO:
     * explain/document the difference between a template and a view
     * 
     * TODO:
     * Implementing the Render/View operation as a class, using the same-
     * name methods with Laraver or CodeIgniter could be good idea;
     * and get rid of if(!defined('OUTPUT_STARTED')) define('OUTPUT_STARTED',1);
     */
    public static function template($template, $data) {

        $file = VIEWS_DIRECTORY . $template . '.php';

        if (file_exists($file)) {
            if (!defined('OUTPUT_STARTED'))  define('OUTPUT_STARTED', 1);

            ob_start();

            extract( self::sanitize_output($data) );
            require( $file );

            ob_flush();

        } else if (!PRODUCTION) {

            echo "<!-- view ". $file ." is missing -->";
        }

    }

    /** parse_sections
     * ---
     * parse a group of views (sections)
     * @param $sections: an array of sections
     * 
     * each section group is an array with view, key and data properties
     * + view: defines the view template/file 
     * + key: the variable name that view uses to parse data OR empty-string*
     *        * if key is an empty then $data should be an array (which
     *        includes all [variable-name:data] pairs utilized by the view)
     * + data: holds the actual data
     */

    public static function sections($sections) {

        foreach($sections as $sect) {

            if ($sect['key'] == '') {
                self::view( $sect['view'], $sect['data'] );

            } else {
                self::view( $sect['view'], [ $sect['key'] => $sect['data']] );
            }
        }
    }


    /** render function
     * ---
     * uses php's short-tag syntax for templating system
     * extract data into template
     * @param $view: view-template filename
     * @param $data: data to embed into view-template
     * @param $sanitize: of true then sanitize data.
     * important NOTE: data is an array [key => value]
     */
    public static function view($view, $data=[], $sanitize = false) {

        $file = VIEWS_DIRECTORY . $view . '.php';

        if (file_exists($file)) {
            if (!defined('OUTPUT_STARTED'))  define('OUTPUT_STARTED', 1);

            extract( $sanitize ? self::sanitize_output($data) : $data );
            require( $file );

        } else if (!PRODUCTION) {

            echo "<!-- view ". $file ." is missing -->";
        }

    }


    /** render asap
     * ---
     * render_view then output code 
     * so that client will get html to render
     * while server calculates next html
     */
    public static function asap($view, $data, $sanitize = false) {
        self::view($view, $data, $sanitize);
        ob_flush();
    }


    /** render text
     * ---
     * This function simply echoes text
     * with Content-Type and Cache Headers
     * @param $data : the text to echo
     * @param $cType : Content-Type header
     * @param $ttl : cache-contol headers; if false response is not-cached; else (int) chache for $ttl seconds
     */
    public static function text( $data, $contentType = 'text/html; charset=UTF-8', $ttl = false ) {
        header('Content-Type: '. $contentType);
        if ($ttl) {
            $ts = gmdate("D, d M Y H:i:s", time() + $ttl) . " GMT";
            header("Expires: {$ts}");
            header("Pragma: cache");
            header("Cache-Control: max-age={$ttl}");

        } else {
            $ts = gmdate("D, d M Y H:i:s") . " GMT";
            header("Expires: {$ts}");
            header("Last-Modified: {$ts}");
            header("Pragma: no-cache");
            header("Cache-Control: no-cache, must-revalidate");
        }
        echo htmlspecialchars($data);
    }


    /** reply_json
     * ---
     * transform a php-array to json and echo to client
     * Can be used for API calls
     *
     * @param $data : the php array)
     * @param $ttl : cache-contol headers; if false response is not-cached; else chache for $ttl seconds
     */
    public static function json( $data, $ttl = false ) {
        header('Content-Type: application/json');
        if ($ttl) {
            $ts = gmdate("D, d M Y H:i:s", time() + $ttl) . " GMT";
            header("Expires: {$ts}");
            header("Pragma: cache");
            header("Cache-Control: max-age={$ttl}");

        } else {
            $ts = gmdate("D, d M Y H:i:s") . " GMT";
            header("Expires: {$ts}");
            header("Last-Modified: {$ts}");
            header("Pragma: no-cache");
            header("Cache-Control: no-cache, must-revalidate");
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);

        // NOTE:
        // after Rendering a JSON ...
        // you probably do not need to export anything else;
        die();
    }




    // HELPER FUNCTIONS
    // -----------------------------------------------------------------------------


    /** link asset
     * ---
     * the function defines the assets (css or js)
     * to be loaded on the client (creates the HTML)
     * @param $type : type of asset [css|js]
     * @param $assetIDs : an array of ('assetID' => 'paramaters')
     *
     * example calls:
     * load_asset('css', ['main' => 'media="all"', 'filters' => 'media="all"']);
     * load_asset( 'js', ['jquery' => '', 'lazyloader' => 'async']);
     */
    public static function asset( $type, $assetIDs, $paramatres = '' ) {

        $code = "";     // code to return
        
        // make sure $assetIDs is array (for coding simplicity)
        if (!is_array($assetIDs)) {
            $assetIDs = [ $assetIDs ];
        }

        // check asset type and construct all asset inserts
        switch ($type) {

            case 'font':
                foreach($assetIDs as $asset) {
                    $code .= "\n\t<link href='". FONTS_DIR . FONT_FILES[$asset] ."' as='font' type='font/woff2' {$paramatres}/>";
                }
                break;

            case 'js':
                foreach($assetIDs as $asset) {
                    $code .= "\n\t<script src='". JS_DIR . JS_LIBRARIES[$asset] ."' id='{$asset}' {$paramatres}></script>";
                }
                break;

            case 'css':
            default:
                foreach($assetIDs as $asset) {
                    $code .= "\n\t<link rel='stylesheet' href='". CSS_DIR . CSS_FILES[$asset] ."' id='{$asset}' {$paramatres} />";
                }
        }

        echo $code;
    }


    /** sanitize_output (recursive) 
     * ---
     * Sanitizes data that are about to rendered
     * (usually when render_view() is called).
     * 
     * NOTE:
     * mitigates XSS attachs
     * 
     * @param $data: (array)
     */
    public static function sanitize_output($data) {
        //// check https://stackoverflow.com/questions/2002710/php-how-to-perform-htmlspecialchar-on-an-array-of-arrays
        
        //// $output = array_map("myFunc", $data);
        
        global $secure;

        $output = array();
        foreach($data as $key => $val) {

            if (is_string($val)) {
                $output[$key] = htmlspecialchars(self::remove_invisible_characters($val));
                
            } else if (is_array($val)) {
                $output[$key] = self::sanitize_output($val);

            } else {
                $output[$key] = $val;
            }
        }
        return $output;
    }


    /** remove_invisible_characters()
     * ---
     * @used by sanitize_output()
     */
    public static function remove_invisible_characters($str, $url_encoded = TRUE)
    {
        $non_displayables = array();

        // every control character except newline (dec 10),
        // carriage return (dec 13) and horizontal tab (dec 09)
        if ($url_encoded) {
            $non_displayables[] = '/%0[0-8bcef]/i';	// url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/i';	// url encoded 16-31
            $non_displayables[] = '/%7f/i';	// url encoded 127
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

        do {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);

        return $str;
    }


    /** html
     * ---
     * outputs code as html
     * 
     * @param $str (string)
     * @return html5 (string)
     */
    public static function html($str) {
        if (!isset($str) || $str== null)        return;
        if (($str == '') || is_numeric($str))   return $str;
        return htmlspecialchars_decode($str, ENT_QUOTES|ENT_HTML5);
    }


    /** set_headers
     * ---
     * set custom response-Headers
     *
     * @param $contentType
     * @param $ttl: int or false)
     * @param $more: array of (content-type => content-value) pairs
     */
    public static function set_headers($contentType = 'text/html; charset=UTF-8', $ttl = false, $more = [] ) {

        // handle common content-type shorcuts
        switch ($contentType) {
        case 'text':
        case 'html':
            $contentType = 'text/html; charset=UTF-8';
            break;
        case 'json':
            $contentType = 'application/json; charset=utf-8';
            break;
        case 'js':
            $contentType = 'application/javascript; charset=utf-8';
            break;
        case 'css':
            $contentType = 'text/css';
            break;
        default:
            // $contentType stays as-is
            break;
        }

        // send headers
        header('Content-Type: '. $contentType);
        if ($ttl) {
            $ts = gmdate("D, d M Y H:i:s", time() + $ttl) . " GMT";
            header("Expires: {$ts}");
            header("Pragma: cache");
            header("Cache-Control: max-age={$ttl}");

        } else {
            $ts = gmdate("D, d M Y H:i:s") . " GMT";
            header("Expires: {$ts}");
            header("Last-Modified: {$ts}");
            header("Pragma: no-cache");
            header("Cache-Control: no-cache, must-revalidate");
        }

        // send more headers
        if ($more != []) {
            foreach($more as $header => $value) {
                header($header .': '. $value);
            }
        }

    }


    /** render file
     * 
     */
    public static function file($path, $madia_type)
    {
        $content = file_get_contents($path);
        header("Content-Type: {$media_type}");
        echo $content;
    }


}