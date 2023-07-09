#!/usr/local/bin/php
<?php

/** curl-cache
 * regenerates/refreshes caches
 * ------------------------------------------------------
 * 
 * This scipt makes requests to the project's web API,
 * These API calls SHOULD be impemented to cache some
 * resource-expensive queries, so in result the script
 * shall manage to create cache.
 * 
 * NOTE:
 * The script does not implement the anom framerork
 * and it is provided for documentation puproses.
 * 
 * ------------------------------------------------------
 */

// some defines and an into message
// -----------------------------------------------------------------------------

require_once 'micro/term_utilities.php';      // terminal utilites

define('API_ROOT','http://127.0.0.1:8080/api/');

define('BATCH_LENGTH', 3);  // see notes later on the script



echo "\033[32mInterface: ". php_sapi_name() ."\033[39m\n\n";

$t0 = microtime(true);

/** supplumentary functions (curl)
 * -----------------------------------------------------------------------------
 */

/** single Curl get call
 * 
 * @param $call (string): url that needs to be called
 * 
 */
function singleCurl($call) {
    $cSRC = curl_init();    // create curl resource for SouRCe end-point
    curl_setopt($cSRC, CURLOPT_URL, API_ROOT.$call);    // set url
    curl_setopt($cSRC, CURLOPT_RETURNTRANSFER, 1);      //return the transfer as a string
    $output = curl_exec($cSRC);         // $output contains the output string in json format
    curl_close($cSRC);                  // close curl resource to free up system resources

    $json = json_decode( $output );     // decode json to php array

    if (!$json->success) {
        echo "\033[91mError reading " . $call ."\n\033[39m";
        return false;
    }

    return $json;
}

/** multiple curl get calls
 * 
 * @param $array: an array of urls to be called
 * 
 */
function multiCurl($array) {

    $multiCurl = array();       // array of curl handlers
    $result = array();          // data to be returned
    $mh = curl_multi_init();    // multi handler
    $module = array();          // array of module names / 1:1 to $result array

    foreach ($array as $i => $category) {
        $module[$i] = $category->ID;    // guid (module name)
        $fetchURL = API_ROOT .'url/'. $category->FullFriendlyUrl;
        $multiCurl[$i] = curl_init();
        curl_setopt($multiCurl[$i], CURLOPT_URL,$fetchURL);
        curl_setopt($multiCurl[$i], CURLOPT_HEADER,0);
        curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER,1);
        curl_multi_add_handle($mh, $multiCurl[$i]);
    }

    $index=null;
    
    do {
        curl_multi_exec($mh,$index);
    } while($index > 0);

    foreach($multiCurl as $k => $ch) {
        $result[$k] = curl_multi_getcontent($ch);
        curl_multi_remove_handle($mh, $ch);
    }
    curl_multi_close($mh);    // close multi-curl
}

// force printing string with specified length
function forceLen($str, $len = 72) {
    $space = " ";
    for($i = 0 ; $i<$len ; $i++) $space .= ".";
    return mb_substr($str.$space, 0, $len-1) ." ";
}


/** main procedure
 * -----------------------------------------------------------------------------
 */

echo "\033[39mReading Tree of product categories...\n";
$tree = singleCurl('tree');     // ask for categories tree

if ($tree !==false) echo "\033[32mCategories tree is cached\033[39m\n\n";

$categories = singleCurl('category');

// request every category to create cache if not exist
// send requests in small batches to avoid enormous server-stress
echo "\033[39mRequesting re-Cache for every category\033[39m\n";
echo "\033[33mPlease Wait...\n\n";

$buffer = [];   // buffer array
$counter = 0;   // counter to track items in buffer
foreach($categories->result as $category) {

    echo "\033[39m". forceLen($category->Title) ."\033[33m"."Sent.\n\033[39m";

    $counter++;

    // try several batch lengths to establish the ideal one that produces
    // results in a smooth quite fase rthm, without stressing the server;
    // so that visitors can navigate the production site white the script
    // is running;
    // for my testing this number is 3-5. I set the cou 3 items give fast results without stressing the server
    // so "if ($counter > 2) { ... " is the smoothest option
    if ($counter > 2) {
        multiCurl($buffer);
        $counter = 0;
        $buffer = [];
    }

    $buffer[] = $category;

}

multiCurl($buffer);     // run once more for the last non-filled buffer

$dt = number_format( microtime(true) - $t0 , 1) ."sec";

// echo ending...
echo "\n\033[32mOperation Completed \033[39m({$dt})\n\n";
echo "\033[39m";    // back to default color;


// check
// http://www.idein.it/joomla/14-docker-php-apache-with-crontab
?>