<?php
/** Micro anom framework
 * minimum setup with the rich features of the framework;
 * 
 * in a symbolic way: { Micro_anom summarizes anom }
 * -----------------------------------------------------------------------------
 * 
 * (+) Use it mainly in cli-interface scripts to
 *     access your application's data intarnaly
 * 
 * (+) Implement routine tasks and schedule the
 *     execution using cron deamon
 * 
 * (+) Avoid public/web-based API calls
 * 
 * (+) Create batch proccessing scripts re-using
 *     your project's implemented methods;
 * 
 * Don't haves
 * ---
 * In your cli-interfaced micro-framework you most
 * probably won't need some of the anom's  central
 * objects (Request, Router, Session, Contollers).
 * 
 * Controller is the script, Router is the scheduler,
 * Session and Authentication is unnecessary (just 
 * put an...
 * ```if (php_sapi_name() != 'cli') return false;```
 * and no call will access the code but the cli.
 * 
 * 
 * What you do have?
 * ---
 * + Database and Cache objects,
 * + all the implemented methods,
 * + Proxy, Repositories etc...
 * 
 * and these are more than enough to write easily
 * a fast, secure, powerful script that handles
 * your data in every way you need.
 */

echo textColor("Setup environment....", GREEN) ."\n";

// get constants
// -----------------------------------------------------------------------------
require_once realpath(MINI_APP_BASE.'../config/constants.php');

// anom typical defines
// -----------------------------------------------------------------------------
define('PRODUCTION', false);

define('APP_NAME', 'e-Classroom');    // Application Name

define('APP_VERSION', 'v0.1');                  // Application version

define('APP_ROOT', realpath(MINI_APP_BASE.'../../public'));

// Cache driver and  caching TTLs
// -----------------------------------------------------------------------------
define('CACHE_DRIVER', 'FileCache');

// Root objects like product-categories tree
define('CACHE_ROOT_TTL', 28800);        // 8 hours

// Product Category
define('CACHE_CATEGORY_TTL', 18000);    // 5 hours

// Product
define('CACHE_PRODUCT_TTL', 3600);      // 1 hour

define('FILECACHE_PATH', realpath(MINI_APP_BASE.'../../storage/cache/').'/');

echo "Cache Directory is ". textColor(FILECACHE_PATH, GREEN)."\n";


// database
// -----------------------------------------------------------------------------

$ini_array = parse_ini_file(realpath(MINI_APP_BASE."../auth/.env"));
define('DB_NAME', $ini_array['DB_NAME']);
define('DB_USER', $ini_array['DB_USER']);
define('DB_PASS', $ini_array['DB_PASS']);
define('PDO_HOST', $ini_array['PDO_HOST']);
echo "Application database is ". textColor(DB_NAME, GREEN) ."\n";

define('DB_TIMEZONE', "SET time_zone = 'Europe/Athens'");


// load autoloader
// -----------------------------------------------------------------------------
require_once realpath(MINI_APP_BASE.'../../vendor/autoload.php');



// Attache database to the Registry a vow
Registry::vow('database', function() { return new Database(); });


// Attach Cache to the Resitry as a vow 
Registry::vow('cache', function() { return new (CACHE_DRIVER)(); });


require_once realpath(MINI_APP_BASE.'../helpers/design_patterns.php');


# Registry::set('reg', 'Registry is working');
# echo Registry::get('reg'), "\n\n";


# NOTE:
# php does not provide error and exception handling
# for the cli interface; you still can handle the
# exceptions using standard try {...} catch { }
# 
# try {
#     // code
# } catch (Exception $exc) {
#     echo 'Caught exception: ', $exc->getMessage(), "\n"
# }