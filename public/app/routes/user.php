<?php 

use anom\core\Route;
use anom\core\Render;
use anom\core\Registry;

use app\controllers\Auth;
use app\controllers\Office;
use app\models\Content_model;
use app\models\Access_model;


/** User Connection Management Routes
 * -----------------------------------------------------------------------------
 */

// common requests (GET method)
// --- -- -- - - -

// request logout
Route::add('/logout', function() {
    Auth::logout();
    header('Location: /');  die();
});



// routes for connected users
////////////////////////////////////////////////////////////////////////////////

if (Auth::is_connected()) {

    // login while authenticated ... -> redirect to panel
    Route::add('/login', function() { header('Location: /panel'); });

    // set invitation while authenticated ... -> redirect to panel
    Route::add('/invitation/([0-9a-f]*)', function($id) { header('Location: /panel'); });


    // SERVE content
    // -------------------------------------------------------------------------

    // control panel
    // --- -- -- - - -
    Route::add('/panel', function() {
        Render::view('user/panel',[
            'is_admin' => Auth::in_admin_group(),
            'content' => 'info',
            'user' => json_decode(json_encode(
                Auth::user_data(), JSON_UNESCAPED_UNICODE
            ))
        ]);
    });

    // request PAGE list-my-petitions
    // --- -- -- - - -
    Route::add('/user/petitions', function() {
        Render::view('user/panel',[
            'is_admin' => Auth::in_admin_group(),
            'content' => 'own_petitions',
            'user' => json_decode(json_encode(
                Auth::user_data(), JSON_UNESCAPED_UNICODE
            ))
        ]);
    });

    // requst RESULTS list my petitions
    Route::add('/user/get/petitions', function() {
        Office::user_petitions();
    });

    // request NEW petition FORM
    // --- -- -- - - -
    Route::add('/petition/([0-9a-z\-_]*)', function($tag) {
        Office::request_petition($tag);
    });

    // request EXISTING petition copy
    // --- -- -- - - -
    Route::add('/petition/signature/([0-9a-f]*)', function($signature) {
        Office::petition_copy($signature);
    });



    // UPDATE user properties
    // -------------------------------------------------------------------------

    // GET HTML: request UPDADE (properties) FORM
    // --- -- -- - - -
    Route::add('/user/update_form', function() {
        Auth::update_form();
    });

    // POST: update user (data via post)
    // --- -- -- - - -
    Route::add('/user/update/properties', function() {
        Auth::update_user();
    }, 'post');


    // GET HTML: request (update) PASSWORD FORM
    // --- -- -- - - -
    Route::add('/user/password_form', function() {
        Auth::password_form();
    });

    // POST: update user (data via post)
    // --- -- -- - - -
    Route::add('/user/update/password', function() {
        Auth::update_password();
    }, 'post');




    // CRUD content
    // -------------------------------------------------------------------------

    // add petition
    // --- -- -- - - -
    Route::add('/petition/new', function() {
        Office::add_petition();
    }, 'post');

}



// routes for administrators
////////////////////////////////////////////////////////////////////////////////

if (Auth::in_admin_group()) {

    // GET HTML: administer petitions (page)
    // --- -- -- - - -
    Route::add('/admin/petitions', function() {
        Render::view('templates/admin_petitions');
    });


    // GET HTML: administer users (page)
    // --- -- -- - - -
    Route::add('/admin/users', function() {
        Render::view('templates/admin_users');
    });


    // GET JSON: request RESULTS list all petitions
    Route::add('/admin/get/petitions', function() {
        Render::json([
            'success' => true,
            'data' => Content_model::all_petitions()
        ]);
    });


    // GET JSON: request RESULTS list all petitions
    Route::add('/admin/get/users', function() {
        Render::json( Registry::use('database')->runQuery(
            "SELECT * FROM user ORDER BY last_name, first_name", []
        ));
    });


    // POST AJAX: set petition protocol
    Route::add('/admin/set/protocol', function() {
        Office::set_protocol();
    }, 'post');


    // GET AJAX: activate user (with user-id)
    Route::add('/admin/user/activate/([0-9]*)', function( $uid ) {
        Render::json([ 'success' => Access_model::activate_user($uid) ]);
        // TODO: Cache_service::update_teachers();
    });


    // GET AJAX: depricate user (with user-id)
    Route::add('/admin/user/deprecate/([0-9]*)', function( $uid ) {
        Render::json([ 'success' => Access_model::deprecate_user($uid) ]);
        // TODO: Cache_service::update_teachers();
    });


    // invite user(s)
    // --- -- -- - - -
    Route::add('/admin/invite', function() {
        $teachers = Registry::use('database')->runQuery(
            "SELECT id, concat(last_name, ' ', first_name) as TeacherName
            FROM user
            WHERE (
                otp_expiration < :now OR otp_expiration IS NULL
            ) AND deprecated IS NULL
            ORDER BY TeacherName",
            [ ':now' => time() ]
        );
        Render::view('templates/invite', ['teachers' => $teachers]);
    });


    // POST AJAX: set petition protocol
    // --- -- -- - - -
    Route::add('/admin/send/invitation', function() {
        Office::send_invitation();
    }, 'post');

}


// temporary (on development)
////////////////////////////////////////////////////////////////////////////////
// NOTE: these is only for testing purposes

// test connection
if (!PRODUCTION) {

    Route::add('/check/connection', function() {
        Render::json( Auth::is_connected() );
    });

}
