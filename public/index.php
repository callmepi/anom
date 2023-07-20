<?php
/** anom
 * ANOther MVC framefork ///////////////////////////////////////////////////////
 * -----------------------------------------------------------------------------
 * 
 * This is where the whole thing stats.
 * Let's keep it simple and clear
 * 
 * anom is an(other) Object-Oriented MVC php-framework.
 * It is a super-light, fast and extensible.
 * 
 * Contains implementations of almost anything you need
 * to start and optimize a server-based web-application.
 * And as long as you write code using secure practices,
 * it shall be secure.
 * 
 * Copyleft: George Halkiadakis
 * Licence: BSD
 * 
 * \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 * -----------------------------------------------------------------------------
 */

use anom\core\Registry;
use anom\core\Render;

// Define the Base Paths
// -----------------------------------------------------------------------------
$dir = explode('/', __DIR__);
$pub = array_pop($dir);

define('APP_ROOT', implode('/',$dir));  // App's root directory
define('WEB_ROOT', APP_ROOT.'/'.$pub);  // the public directory


// Load parametres:
// paths, contants and central arrays
// -----------------------------------------------------------------------------
require_once WEB_ROOT.'/app/config/setup_framework.php';        // application constants



// Enable Autoloader 
// -----------------------------------------------------------------------------
require_once AUTOLOADER;



// Enable sessions
// -----------------------------------------------------------------------------
$session = new (SESSION_DRIVER)();	                // Start a new session



// prepare output control and benchmarking
// -----------------------------------------------------------------------------
ob_start();                                         // handle output
if (!PRODUCTION)   \anom\core\Benchmark::add_spot('start');    // benchmark request's life



// Initialize Application
// finalize framework; setup app-engine; insert routes; investigate request
// -----------------------------------------------------------------------------
require_once APPLICATION_BOOTSTRAP;



// Application is ready!
// -----------------------------------------------------------------------------

Route::run(Registry::get('REQUEST'));               // Run baby, run!



if (!PRODUCTION)   \anom\core\Benchmark::add_spot('end');      // request ended
if (!PRODUCTION)   echo( \anom\core\Benchmark::report());      // echo benchmark report
ob_flush();                                         // flush output
