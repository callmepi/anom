<?php

use anom\core\Route;
use anom\core\Render;

use app\controllers\Auth;


// Home/Welcome
////////////////////////////////////////////////////////////////////////////////

// welcome -> redirecto to login
Route::add('/', function() { header("Location: /login");; });



// Error pages
////////////////////////////////////////////////////////////////////////////////

Route::notFound(        function() {        // 404 error page
    header("HTTP/1.0 404 Not Found");
    Render::view('error/404', ['message' => 'nobody knows it (but you got a secret smile;)']);
});



// NOTE: this set of routes defines all valid NON-AUTHenticated requests
////////////////////////////////////////////////////////////////////////////////

if (!Auth::is_connected()) {

    // request login
    // render Form -> will POST: /account/check-login
    // --- -- -- - - -
    Route::add('/login', function() { Render::view('user/login'); });


    // user sends login form
    // --- -- -- - - -
    Route::add('/account/check-login', function() {
        $response = Auth::login();
        Render::json(['status' => $response]);
    }, 'post');


    // request invitation (for account activation)
    // render Form -> will POST /account/activate
    // --- -- -- - - -
    Route::add('/invitation/([0-9a-f]*)', function($id) {
        if ($id == "" || $id == "0") { Render::view('error/404');
        } else {
            Auth::invitation($id);
        }
    });


    // POST invitation = activate
    // --- -- -- - - -
    Route::add('/account/activate', function() { Auth::activate(); }, 'post');

}




/** Examples of real-world route formats
 * 
 * URL-format Proposals and common Route regex solutions
 * -----------------------------------------------------------------------------
 * 
 * Page (almost-static content):
 * /about/{url}
 # Route::add('/about/([0-9a-zA-Z-_\/]*)', function($url) { Page::fromUrl($url); });
 *
 * Product:
 * /{level-1}/{level-2}/{level-3}/{friendly-url}-{id}
 # Route::add('/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)-([0-9]*)',
 #     function($category, $subcategory, $group, $label, $id) {
 #         Product::fromID([$$category, $subcategory, $group, $label],  $id);
 #     }
 # );
 *
 * Category
 * /{category}/[ {subcategory}[ /{group}]]
 # Route::add('/([0-9a-zA-Z-_]*)', function($a) { Category::url([$a]); };
 # Route::add('/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)', function($a, $b) { Category::url([$a, $b]); });
 # Route::add('/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)', function($a, $b, $c) { Category::url([$a, $b, $c]); });
 * 
 * othet routes used in some user-cases
 *
 # Route::add('/lesson/([0-9a-f]*)/([0-9a-zA-Z-_]*)',
 #     function($ticket, $lesson) { Lesson::show([$lesson, $ticket]); }
 # );
 # 
 # Route::add('/media/([0-9a-f]*)/([0-9a-zA-Z-_]*)/([0-9]*)/',
 #     function($ticket, $type, $id) { Media::serve([$type, $id, $ticket]); }
 # );
 *
 * 4-th level paths belong to products
 # Route::add('/about/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)',
 #     function($a, $b, $c) { Page::show([$a, $b, $c]); }
 # );
 *
 * 
 * OTHER EXAMPLES (use it for brainsrtorming)
 * -----------------------------------------------------------------------------
 * 
 * 
 # // Typical Use
 # // ---
 # Route::add('/art',              function() { Art::blah(); });
 # Route::add('/art/db',           function() { Art::fromDatabase(); });
 # Route::add('/art/([0-9]*)',     function($id) { Art::getInfo($id); });
 *
 * Get-Post route example
 # Route::add('/contact-form',     function() {
 #     echo '<form method="post"><input type="text" name="test" /><input type="submit" value="send" /></form>';
 # }, 'get');
 
     Route::add('/contact-form',     function() {
         echo 'Hey! The form has been sent:<br/>'; print_r($_POST);
     }, 'post');
 *
 * Accept number as parameter
 # Route::add('/foo/([0-9]*)/bar', function($var1) {
 #     // echo $var1.' is a number!';
         echo "{$var1} is a number!";
 # });
 *
 * About pages
 # Route::add('/about/([0-9a-zA-Z-_]*)', function($page) {
 #     InfoPage::render('about/'.page);
 # });
 *
 * handle a request like: /foo/123/bar/3254-lefki-zaxari-marata-1kg
 * where first-numeric-part of last-uri-section (3254) is the product number
 # Route::add('/foo/([0-9]*)/bar/([0-9]*)-([0-9a-zA-Z-_]*)',   function($num, $id, $name) {
 #     echo $num .' is some nomber, id = '. $id .' and label = '. $name;
 # });
 *
 * handle a request like: /zaxares-glykantika/lefki-zaxari-year-2022-45678
 * where last-numeric-part of last-uri-section (45678) is the product number
 # Route::add('/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)-([0-9]*)',
 #     function($category, $subcategory, $group, $label, $id) {
 #         Shop::product([$$category, $subcategory, $group, $label],  $id);
 # });
 *
 * last chance to resolve (some friendly url)
 # Route::add('/([0-9a-zA-Z-_]*)',       function($a) { Resolve::uri([$a]); });
 # Route::add('/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)',  function($a, $b) { Resolve::uri([$a, $b]); });
 # Route::add('/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)',   function($a, $b, $c) {  Resolve::uri([$a, $b, $c]); });
 *
 * 
 * Product page
 * handle a request like: /pantopoleio/zaxares/lefki-zaxari/lefki-zaxari-year-2022-45678
 * where last-numeric-part of last-uri-section (45678) is the product number
 # Route::add('/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)/([0-9a-zA-Z-_]*)-([0-9]*)',
 #     function($category, $subcategory, $group, $label, $id) {
 #         Shop::product([$$category, $subcategory, $group, $label],  $id);
 # });
 *
 * ------------------------------------------------------------------------------
*/
