<?php
/** CONFIGURATION PARAMETRES
 * -----------------------------------------------------------------------------
 * 
 * Here you can find all the setup paramaters that define
 * key-decisions of your application;
 * 
 * ex. Session driver, Cache driver, Cookies Policy etc;
 * 
 * Other supplamentary constants according to your application's logic
 * shall be defined at the end of this file, or in some extra file(s),
 * ex: ~/public/app/config/setup-application.php
 * 
 * 
 * NOTE:
 * You may rewrite the defines setting any values manualy;
 * SETTING anom CONSTANTS VIA an `.env` FILE IS HIGHLY RECOMMENDED;
 * The default `.env` file is saved into project's root
 * 
 * Blueprint:
 *  
 * -----------------------------------------------------------------------------
 */


/** Preload environmental file
 * -----------------------------------------------------------------------------
 * 
 * Setting up main constants via en envoronment file is HIGHLY RECOMMENDED;
 * read ini file if exist
 * 
 * -----------------------------------------------------------------------------
 */
if (file_exists(APP_ROOT ."/.env")) {
  $env = parse_ini_file(APP_ROOT. "/.env");
}




## -----------------------------------------------------------------------------
##
##       - - - -- -- ---  M A I N  C O N S T A N T S  --- -- -- - - -
##  Main constants (names, labels, security options, optimzation defaults etc)
##
## -----------------------------------------------------------------------------



/** ENVIRONMENT constant ///////////////////////////////////////////////////////
 * -----------------------------------------------------------------------------
 * 
 * define application's environment stage; [PRODUCTION|staging|develpment|testing|*]
 * seting this constant to 'PRODUCTION' eliminates debuggin comments;
 * 
 * DO NOT FORGET to change this setting when deploying to a different stage
 * 
 * -----------------------------------------------------------------------------
 */

define('PRODUCTION', ($env['ENVIRONMENT'] == 'PRODUCTION') );




/** APPLICATION NAMES AND URL DEFAULTS /////////////////////////////////////////
 * -----------------------------------------------------------------------------
 * 
 * NOTE:
 * if the `.env` file exists ... do not touch anything
 * else ... replace $env[*] values with your setup/defaults
 * 
 * -----------------------------------------------------------------------------
 */

    // Application Name (how developer refers to it)
define('APP_NAME', $env['APP_NAME'] ?? 'anom.App');

    // Site Title (the end-users' title)
define('SITE_TITLE', 'schoolGov');      

    // Application version (major_version + subversion_class) keep it simple
define('APP_VERSION', $env['APP_VERSION']);     // ex: '1.alfa' -or- '2.rc' etc.

    // root url of the web app
define('SITE_URL', $env['ANOM_HOSTNAME']);      




/** APPLICATION PATHS //////////////////////////////////////////////////////////
 * -----------------------------------------------------------------------------
 * 
 * Obligarory:
 * These are needed in order to run the bare minimum MVC system
 * They also make code easier to oranize and navigate into.
 * 
 * NOTE:
 * most likely you do not need to change any of these subsection defines;
 * 
 * -----------------------------------------------------------------------------
 */

define('URL_ROOT', '/');

// main initialization script
define('APPLICATION_BOOTSTRAP', WEB_ROOT.'/app/config/bootstrap.php');

define('VIEWS_DIRECTORY', $env['VIEWS_DIRECTORY'] ?? (WEB_ROOT.'/app/views/'));
 
define('TESTS_DIRECTORY', $env['TESTS_DIRECTORY'] ?? (APP_ROOT."/tests/"));




/** AUTOLOADER /////////////////////////////////////////////////////////////////
 * -----------------------------------------------------------------------------
 * 
 * An autoloader for classes is required;
 * 
 * 
 ** OPTION 1: COMPOSER
 * -----------------------------------------------------------------------------
 * Composer is recommended and makes much more than simple autoloding
 * -----------------------------------------------------------------------------
 */

define('AUTOLOADER' , APP_ROOT.'/vendor/autoload.php');

/** OPTION 2: Custom Autoloader
 * -----------------------------------------------------------------------------
 * If Composer is not supported, another autoloading proccess required
 * Comment/Disable the 1st option and uncomment/Enable the next line
 * -----------------------------------------------------------------------------
 * 
 * NOTE: if the custom autoloadet is used
 * then you need to define CLASSPATHS constant-array too;
 */

# define('AUTOLOADER' , '../core/helpers/autoload.php');
 
/** Autoload paths 
 * -----------------------------------------------------------------------------
 * 
 * CLASSPATHS : array of paths where classes are saved
 * 
 * NOTE:
 * if the custom autoloader is used (instead of composer's)
 * then you need to defing CLASSPATHS constant-array too;
 * 
 * In most cases you won't need this,
 * so comment the next lines out of the code-base
 * 
 * -----------------------------------------------------------------------------
 */
# define('CLASSPATHS', array(
#     APP_ROOT .'/core/classes/',       // core classes
#     WEB_ROOT .'/app/controllers/',    // controller classes
#     WEB_ROOT .'/app/models/',         // model classes
#     WEB_ROOT .'/app/extends/',        // extending core classes
#     APP_ROOT .'/vendor/'              // vendor/dependency classes
#   )
# );




/** SESSION HANDLING ///////////////////////////////////////////////////////////
 * -----------------------------------------------------------------------------
 * 
 * there are several session drivers for session handling:
 *    
 * 'DefaultSession'  : uses the default (file-based) php session management
 * 'FileSession'     : defines a custom file-based session mechanism
 * 'DatabaseSession' : mechanism that stores Session data into a DataBase
 * -----------------------------------------------------------------------------
 */

define('SESSION_DRIVER', $env['SESSION_DRIVER'] ?? 'DefaultSession');



/** FileSession Path
 * --- -- -- - - -
 * if FileSession is selected, a path for session-files is needed
 * 
 * TODO:
 * also, the hybrid sessioning (in progress) will need this setting
 * 
 * NOTE:
 * DefaultSession is also file-based but to not need a file-path setting
 * (php will handle any file-needed actions to the file-system)
 */

# define('FILESESSION_PATH', '../storage/session');



/** COOKIE SETTINGS ////////////////////////////////////////////////////////////
 * -----------------------------------------------------------------------------
 * 
 * Define cookie policy (cookie sharing and securing)
 * 
 * recomended values for production environment:
 * COOKIE_SECURE=true
 * COOKIE_SAMESITE='Strict'
 * 
 * NOTE:
 * on development environment COOKIE_SECURE has to be false
 * in order for the test server to use them from http://locahost
 * 
 * for more info about cookie policy read:
 * https://www.php.net/manual/en/function.session-set-cookie-params.php
 * 
 * -----------------------------------------------------------------------------
 */

define('COOKIE_SECURE', PRODUCTION);    // Secure cookie policy [true|false]

define('COOKIE_SAMESITE', $env['COOKIE_SAMESITE'] ?? 'Strict');    // SameSite cookie policy [Strict|Lax|None]
     
# define('COOKIE_DOMAIN', $env['ANOM_HOSTNAME']);   // domain to be visible on




/** Session and cookie names
 * -----------------------------------------------------------------------------
 */
define('SESSION_NAME', 'offticket');    // cookie for session (office ticket)
define('CONNECTION_COOKIE', 'con');     // cookie for connection
define('MAX_SESSION_LIFE', 2*60*60);    // maximum session lifetime (2 hours)




/** SECURITY SETTINGS ///////////////////////////////////////////////////////////
 * -----------------------------------------------------------------------------
 * 
 * Cross Site Request Forgery
 * ---
 * Enables a CSRF cookie token to be set. When set to TRUE, token will be
 * checked on a submitted form. If you are accepting user data, it is strongly
 * recommended CSRF protection be enabled.
 * -----------------------------------------------------------------------------
 */

define('CSRF_PROTECTION', false);

define('CSRF_TOKEN_NAME', 'csrf_test_name');        // token name

define('CSRF_COOKIE_NAME', 'csrf_cookie_name');     // cookie name

define('CSRF_EXPIRE', 7200);            // The number in seconds the token should expire.

define('CSRF_REGENERATE', TRUE);        // Regenerate token on every submission

define('CSRF_EXCLUDE_URIS', array());   // Array of URIs which ignore CSRF checks




/* CACHE DRIVER ////////////////////////////////////////////////////////////////
 * -----------------------------------------------------------------------------
 *
 * APP_CACHE defines the cache-driver;
 * accepted values are the name of the Cache-interface impementations (the exact
 * names of the classes)
 *
 * 'FileCache'  : caching in filesystem; fair if caching on HDD; great on SSD
 * 'RedisCache' : caching in Redis server; generally recommended if available
 * 'MemCachedCache' : caching in Memcached server; recommended if available
 * 
 * You can configure the caching parametres later on this file;
 *
 * NOTE:
 * Nowadays FileCache on a NVMe SDD seems to be the fastest option;
 * Redis and Memcached are really-fast caching options and are available for
 * scaling horizontaly your application (each one having it's own strengths)
 *
 * -----------------------------------------------------------------------------
 */
  
define('CACHE_DRIVER', $env['CACHE_DRIVER'] ?? 'FileCache');


 


## -----------------------------------------------------------------------------
##
## - - - -- -- ---  C O N N E C T I O N   C R E D E N T I A L S  --- -- -- - - -
##
## -----------------------------------------------------------------------------



/** SERVICE PARAMETRES
 * -----------------------------------------------------------------------------
 * 
 * Connection parametres and credentials for accssing services 
 * needed by the application; Such services may be..
 * 
 * - RDBMS
 *   -- MySQL
 *   -- PotgresSQL
 * 
 * - Caching services
 *   -- Redis
 *   -- MemCached
 * 
 * Edit only the constants needed by the application;
 * Comment those you do not need;
 * 
 * /////////////////////////////////////////////////////////////////////////////
 */



/** DATABASE CONNECTION PARAMETRES /////////////////////////////////////////////
 * -----------------------------------------------------------------------------
 * Production and staging environments may use different databases.
 * 
 * A safe practice is to keep credentials outside plain files like this one
 * in environmental variable or other secret file etc.
 * 
 * Uncomment/enable each group of definitions suits your case to define
 * the credentials needed for accessing the database
 */

define('DB_NAME', $env['DB_NAME']);
 
define('DB_USER', $env['DB_USER']);
  
define('DB_PASS', $env['DB_PASS']);
  
/** NOTE:
 * PDO_HOST can be a hostname/port combination -or- a unix socket
 * 
 * ...examples:
 * define('PDO_HOST', 'host=/localhost')    ## hostname case
 * define('PDO_HOST', 'host=/localhost;port=3456')    ## hostname/port case
 * define('PDO_HOST', 'unix_socket=/sql/ex123:europe:some-db');   ## unix socket
 * define('PDO_HOST', 'unix_socket=/cloudsql/name-123456:europe-west4:name-db-eu');
 */
 
define('PDO_HOST', $env['PDO_HOST']);
  
define('DB_TIMEZONE', "SET time_zone = 'Europe/Athens'");
 
 
 
 
/** CACHED SERVICES ////////////////////////////////////////////////////////////
 * -----------------------------------------------------------------------------
 * 
 * impemented services:
 * - File Cache
 * - Redis
 * - Memcached
 * 
 * NOTE:
 * Both Redis and Memcached configurations are given as a template to work on;
 * In most cases the default values should do the job -- of course you need to
 * read the README file (check the project's root);
 * As these caching services are not fully tested you may need to ochestrate
 * the services in detail or edit the connection strings into the core classes
 * (hosted under the '/core/classes/cacher' folder)
 * 
 * -----------------------------------------------------------------------------
 */

define('FILECACHE_PATH', APP_ROOT.'/storage/cache/');     // if CACHE_DRIVER is set to 'FileCache'

define('MEDIA_STORAGE_ROOT', APP_ROOT.'/storage/uploads/');    // Media files root directory
 

// CONNECTIONS (if Redis or Memcached is selected) ...
 

# define('REDIS_HOST', '127.0.0.1');              // if CACHE_DRIVER is set to 'RedisCache'
 
# define('MEMCAHCED_SERVER', '127.0.0.1');        // if CACHE_DRIVER is set to 'MemCached'
 
 
 # define('MEMCAHCED_SERVER', '127.0.0.1');        // if CACHE_DRIVER is set to 'MemCached'
 
/** REDIS SERVICE
 * -----------------------------------------------------------------------------
 * Most of the times Redis is running on 'localhost' (host = '127.0.0.1')
 * When implemented via anom's docker-composer (redis via bridge) then you need
 * do declare the hostname as 'redis'
 */

# define('REDIS_HOST', '127.0.0.1');    // if CACHE_DRIVER is set to 'RedisCache'
# if (!defined('REDIS_HOST'))           define('REDIS_HOST', 'redis');
# 
# if (!defined('REDIS_PORT'))           define('REDIS_PORT', 6379);
# 
# if (!defined('REDIS_PASS'))           define('REDIS_PASS', null);
 
 
/** MEMCACHED
 * -----------------------------------------------------------------------------
 * Memcached looks very much like Redis (plus, both are serving from RAM)
 * Usualy Memcashed is running localy so host is 'localhost' ('127.0.0.1')
 * If you use the framework's docker-composer implementation then
 * the hostname is 'anomemcached'
 */

# if (!defined('MEMCACHE_HOST'))  define('MEMCACHE_HOST', 'anomemcached');
 







/** General Scope Constants ////////////////////////////////////////////////////
 * -----------------------------------------------------------------------------
 * 
 * needed by the framework for common tasks
 * 
 * Anom suggests:
 * + Common Content Types, and
 * + Proxy flags
 * + Benchmark report formats
 * 
 * -----------------------------------------------------------------------------
 */



/** COMMON CONTENT TYPES
 * -----------------------------------------------------------------------------
 */
define('COMMON_CONTENT_TYPES', array(
        // text,css
    'text/html; charset=UTF-8', 'text/css',
        // json
    'application/json; charset=utf-8',
        // javascript
    'application/javascript; charset=utf-8',
        // images    
    'image/png', 'image/jpeg', 'image/web',
        // pdf
    'application/pdf',
        // word
    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        // excel
    'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        // rar
    'application/vnd.rar', 'application/x-rar-compressed', 'application/octet-stream',
        // zip
    'application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/octet-stream',
  )
);



/** PROXY_FLAGS
 * -----------------------------------------------------------------------------
 * These options represend altered/non-default behaviour
 * and will be resolved bitwise, so use powers of 2
 * -----------------------------------------------------------------------------
 */
define('PROXY_CACHE_ERRORS', 1);  // cache result even if error
define('PROXY_IGNORE_CACHE', 2);  // ignore cache if exist
define('PROXY_DO_NOT_CACHE', 4);  // do not cache result



/** BENCHMARK REPORT TYPES
 * -----------------------------------------------------------------------------
 */

define('BENCH_REPORT_ARRAY', 1);    // repost as array
define('BENCH_REPORT_JSON' , 2);    // report as json oblject
define('BENCH_REPORT_HTML' , 3);    // report as HTML code
define('BENCH_REPORT_CODE' , 4);        // report as CODE/text
define('BENCH_REPORT_COMMENT' , 5);     // report as HTML comment




/** need even more constants? //////////////////////////////////////////////////
 * -----------------------------------------------------------------------------
 * 
 * Append them here,
 * or require an extra `constants definition` file
 * 
 * -----------------------------------------------------------------------------
 */

 require_once WEB_ROOT.'/app/config/setup_application.php';
