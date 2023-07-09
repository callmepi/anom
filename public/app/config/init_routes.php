<?php 

// Add Routes
// -----------------------------------------------------------------------------

require_once 'app/routes/api.php';          // API: =/api/{table}/{id};/api/*

// require_once 'app/routes/backend.php';      // Backend: =/admin/*

require_once 'app/routes/user.php';         // User account management

require_once 'app/routes/frontend.php';     // Frontend =/* (whatever)
