<?php
/** initialize System
 * -----------------------------------------------------------------------------
 * 
 * Finalalize the MVC framework and construct the central objects
 * (Registry and Request)
 *
 * Include _anything_ that is common practice on the request life-cycle
 * of your project and does not need to be shown in the /public/index.php
 * Usualy here you shall initialize supplamentary services like Sessions
 * / Database / Caching etc.
 *
 * NOTE:
 * Always try to keep the app as light as possible, so load only what is
 * critical for the MVC to operate;
 * anything else can be injected on-demand
 * 
 * -----------------------------------------------------------------------------
 */

 
use anom\core\Registry;
use anom\core\Request;
use anom\core\Database;


// include error-handliing
// -----------------------------------------------------------------------------
require_once APP_ROOT.'/core/helpers/error_handling.php';



// Setup Application Engiine
// -----------------------------------------------------------------------------
// Setup supplementary services (derectry or as promises/vows)
// Session, Database, Caching


// Attache database to the Regostry a vow
Registry::vow('database', function() { return new Database(); });


// Attach Cache to the Resitry as a vow 
// (CACHE_DRIVER) acts as driver-wrapper
// --- -- -- - - -
Registry::vow('cache', function() { return new (CACHE_DRIVER)(); });
// CHECK: if strict mode has any benefits:
// Registry::vow('cache', function():Cache_interface { return new (CACHE_DRIVER)(); });


// Initialize THE REQUEST
// -----------------------------------------------------------------------------
Registry::set('REQUEST', new Request());



// load design-patterns
// (these patterns use some of the project's central objects (Registry,
// Cache, Database), so laod them after.
// -----------------------------------------------------------------------------
require_once APP_ROOT.'/core/helpers/design_patterns.php';


// Add Routes
// -----------------------------------------------------------------------------

require_once WEB_ROOT.'/app/config/init_routes.php';      // initialize routes
