#!/usr/local/bin/php
<?php

/** cli-cache
 * regenerates/refreshes caches.
 * ------------------------------------------------------
 * 
 * The sctipt implements a micro-anom application
 * that queries directry the project's models.
 * 
 * Advantages vs curl-cache:
 * ---
 * + Runs out of the web/apache interface;
 * + Has less requirements, uses less total resources;
 * + Avoids the typical block/waiting/receiving delays;
 * + (theoretically) it is a faster  implementation
 *
 * ------------------------------------------------------
 */
$t0 = microtime(true);                          // time benchmark

require_once 'micro/term_utilities.php';        // terminal colors

echo textColor("\nInterface: ", NORMAL) . textColor(php_sapi_name(), GREEN). "\n";


/** anom-Cli app initialization
 * ------------------------------------------------------
 */
define('MINI_APP_BASE', __DIR__.'/');
echo "Base directory is ". textColor(MINI_APP_BASE, GREEN) ."\n";

require_once MINI_APP_BASE.'/micro/setup.php';
echo "System is ready;\n";



try {
    # your code goes here ------------------------------------------------------


    // Re-create category_products tree Cache
    app\controllers\cli\CliContoller::cacheCategoriesTree();

    $categories = app\controllers\cli\CliContoller::getCategories();

    
    foreach($categories as $key => $category) {

        echo textColor( textWidth($category['Title'], 72), NORMAL);
        app\controllers\cli\CliContoller::cacheCategoryByUrl($category['FullFriendlyUrl']);
    }

    # end of script ------------------------------------------------------------

} catch(Exception $e) { // Basic error handling
    echo 'Caught exception: ', textColor($e->getMessage(), ERROR), "\n";
    die();
}


$dt = number_format( microtime(true) - $t0 , 0) ."sec";

// echo ending...
echo textColor("\nOperation Completed  {$dt}\n\n", NORMAL);
