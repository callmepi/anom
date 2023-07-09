<?php

//// use app\controllers\Auth;
//// use app\controllers\CmsAdmin;
//// use app\models\admin\Privilege_model;
//// use app\extends\Cache_service;


/** admin pages
 * -----------------------------------------------------------------------------
 * NOTE:
 * All adminitration routes MUST INCLUDE an Auth::allowRoles() check
 */
/*
// admin panel
////////////////////////////////////////////////////////////////////////////////

Route::add('/admin', function () {      // admin panel
    Auth::allowRoles([1, 2, 3]);        // allow few admin-panel roles
    Render::view('admin/skeleton', [
        'title' => 'Διαχείριση Classroom',
        'action' => 'panel',
    ]);
});
Route::add('/admin/panel', function () {    // admin panel #2
    Auth::allowRoles([1, 2, 3]);
    Render::view('admin/skeleton', [
        'title' => 'Διαχείριση Classroom',
        'action' => 'panel',
    ]);
});




// admin lessons
////////////////////////////////////////////////////////////////////////////////

Route::add('/admin/lessons', function () {      // request admin-lessons page
    Auth::allowRoles([1, 2, 3]);        // allow few admin-panel roles
    Render::view('admin/skeleton', [
        'title' => 'Διαχείριση Μαθημάτων',
        'action' => 'lessons',
    ]);
});

Route::add('/admin/edit_lesson', function () {      // new lesson form
    Auth::allowRoles([1, 2, 3]);    // allow few admin-panel roles
    Render::view('admin/skeleton', [
        'title' => 'Νέο Μάθημα',
        'action' => 'edit_lesson',
    ]);
});

Route::add('/admin/edit_lesson/([0-9]*)', function ($id) {      // edit lesson form
    Auth::allowRoles([1, 2, 3]);
    Render::view('admin/skeleton', [
        'title' => 'Επεξεργασία Μαθήματος',
        'action' => 'edit_lesson',
        'id' => intval($id)
    ]);
});


Route::add('/admin/api/lesson/([0-9]*)', function ($id) {       // ajax: get lesson
    Auth::allowRoles([1, 2, 3]);    // allow few admin-panel roles
    CmsAdmin::get_lesson($id);
});


Route::add('/admin/api/lesson/add', function () {           // ajax: add lesson
    Auth::allowRoles([1, 2, 3]);    // allow few admin-panel roles
    Render::json(['success' => CmsAdmin::add_lesson()]);
}, 'post');


Route::add('/admin/api/lesson/update', function () {        // ajax: update lesson
    Auth::allowRoles([1, 2, 3]);    // allow few admin-panel roles
    Render::json(['success' => CmsAdmin::update_lesson()]);
}, 'post');


Route::add('/admin/api/lessons', function () {      // ajax: get all lessons
    Auth::allowRoles([1, 2, 3]);    // allow few admin-panel roles
    CmsAdmin::all_lessons();
});



// admin files 
////////////////////////////////////////////////////////////////////////////////

Route::add('/admin/files', function () {    // admin-files panel
    Auth::allowRoles([1, 2, 3]);
    Render::view('admin/skeleton', [
        'title' => 'Διαχείριση Μέσων (media)',
        'action' => 'files',
    ]);
});


Route::add('/admin/api/files', function () {    // ajax: get lesson
    Auth::allowRoles([1, 2, 3]);
    Render::json(CmsAdmin::files());
});

// ajax: file-upload
// ---
Route::add('/admin/api/file_upload', function () {
    Auth::allowRoles([1, 2, 3]);    // allow few admin-panel roles
    Render::json(CmsAdmin::upload_file());
}, 'post');




// admin page
////////////////////////////////////////////////////////////////////////////////

Route::add('/admin/pages', function () {      // request admin-lessons page
    Auth::allowRoles([1, 2, 3]);        // allow few admin-panel roles
    Render::view('admin/skeleton', [
        'title' => 'Διαχείριση Σελίδων',
        'action' => 'pages',
    ]);
});


Route::add('/admin/edit_page', function () {      // new page form
    Auth::allowRoles([1, 2, 3]);    // allow few admin-panel roles
    Render::view('admin/skeleton', [
        'title' => 'Νέα Σελίδα',
        'action' => 'edit_page',
    ]);
});

Route::add('/admin/edit_page/([0-9]*)', function ($id) {      // edit page form
    Auth::allowRoles([1, 2, 3]);
    Render::view('admin/skeleton', [
        'title' => 'Επεξεργασία Σελίδας',
        'action' => 'edit_page',
        'id' => intval($id)
    ]);
});


Route::add('/admin/api/page/([0-9]*)', function ($id) {       // ajax: get page
    Auth::allowRoles([1, 2, 3]);    // allow few admin-panel roles
    CmsAdmin::get_page($id);
});


Route::add('/admin/api/page/add', function () {           // ajax: add page
    Auth::allowRoles([1, 2, 3]);    // allow few admin-panel roles
    Render::json(['success' => CmsAdmin::add_page()]);
}, 'post');


Route::add('/admin/api/page/update', function () {        // ajax: update page
    Auth::allowRoles([1, 2, 3]);    // allow few admin-panel roles
    Render::json(['success' => CmsAdmin::update_page()]);
}, 'post');


Route::add('/admin/api/pages', function () {      // ajax: get all pages
    Auth::allowRoles([1, 2, 3]);    // allow few admin-panel roles
    CmsAdmin::all_pages();
});

*/