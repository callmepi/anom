<?php

use app\controllers\Auth;
use app\controllers\api\Common_api;
use app\controllers\api\Doc_api;


/** API CALLS
 * -----------------------------------------------------------------------------
 * 
 * these calls return json responses
 */


// DEPRECATED: (not needed)

// api: database documentation
// Route::add('/api/doc',
//     function() {     Doc_api::dbDoc(); },
//     'get'
// );
// 
// Route::add('/api/url/([0-9a-zA-Z-_\/]*)',
//     function($url) {            Common_api::category_by_url($url); }
// );
// 
// // route: /api/table
// Route::add('/api/([0-9a-zA-Z-_]*)',
//     function($table) {          Common_api::table($table); },
//     'get'
// );
// 
// // route: /api/table/{id}
// Route::add('/api/([0-9a-zA-Z-_]*)/([0-9]*)',
//     function($table, $id) {     Common_api::record($table, $id); },
//     'get'
// );



/** XML CALLS
 * -----------------------------------------------------------------------------
 * 
 * These calls are like `/api`, except...
 * they return xml/html as part of the responses;
 * 
 * example: { success: true, message: html }
 * 
 * Used when an html message needs to be passed in some div
 * 
 */




/** TEST CALLS
 * -----------------------------------------------------------------------------
 * 
 * direct calls to simple tests
 * (not on production environment)
 */
 if (!PRODUCTION) {
    Route::add('/test/([0-9a-zA-Z-_\/]*)',    function($test) {
        if (file_exists(TESTS_DIRECTORY . $test .'.php')) {
            if (!Benchmark::exist('start')) Benchmark::add_spot('start');
            require(TESTS_DIRECTORY . $test .'.php');
        } else {    echo "Test {$test} not exist";
        } die();

    });
}
