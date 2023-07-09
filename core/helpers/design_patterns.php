<?php
/** proxy
 * implements the proxy design pattern
 * 
 * uses: Registry::cache
 * 
 * algorithm:
 * - 1: proxy gets (Class::method, Arguments) and calculates a unique key
 * - 2: if a valid Cache for the triplete's key exists, it is served immediately
 * - 3: otherwise calls Class::Method(Arguments), caches then serves response
 * 
 * @param $class_method (array): a 2 (string) items array in the form
 *    : [ 'fully\qualifies\ClassName' , 'methodName' ]
 *    : Using the ClassName::class notation is strongly recommended.
 * @param $args: array of arguments (passed to the Class::Method())
 * @param $ttl: time (in sec) where the data will remain valid (not expired)
 * @param $options: bitwise flags*
 * ---
 * @return $data (mixed)
 * 
 * (*) option flags
 * all option flags represend non default behaviour of proxy
 * ---
 * PROXY_CACHE_ERRORS : cache value even if callback returns error
 * PROXY_IGNORE_CACHE : ignore if cache exist; get result from callback
 * PROXY_DO_NOT_CACHE : do not cache the result (even if from callback)
 */
function proxy(array $callable, array $args, int $ttl, int $options=0)
{   
    // resolve option flags
    $cache_errors = ($options&PROXY_CACHE_ERRORS) ? true : false;
    $ignore_cache = ($options&PROXY_IGNORE_CACHE) ? true : false;
    $do_not_cache = ($options&PROXY_DO_NOT_CACHE) ? true : false;

    // we're gonna use cache;
    $cache = Registry::use('cache');


    // check validity of callable argument
    // -------------------------------------------------------------------------
    if ((!is_callable($callable)) || (!is_array($callable))) {

        throw new Exception(
            'Proxy\'s first parameter has to be a callable array [fully\\qualified\\ClassName, method], passed:'.print_r($callable, true)
        );

    }

    // create a unique key
    // -------------------------------------------------------------------------
    list($class, $method) = $callable;
    $key = sha1(
        $class .':'. $method .':'. json_encode(
            $args, JSON_UNESCAPED_LINE_TERMINATORS|JSON_UNESCAPED_UNICODE
        )
    );

    // search cache if data for this key exist
    // -------------------------------------------------------------------------
    if (!$ignore_cache) {
        $data = $cache->get($key);

    } else { $data = false; }   // ignore cache? pass false


    if ($data !== false) {      // if exists, serve cached data

        // if cashed data exist
        // ---------------------------------------------------------------------
        # if (!PRODUCTION)   Benchmark::add_spot('proxy-cached');
        if ($data == '_ERROR_')   return false;   // previous cached error
        else return $data;  // previous cache ()


    } else {    // else (not exist)...

        // no data on cache -> get data from Class::method( arguments )
        // ---------------------------------------------------------------------
        $data = call_user_func_array($callable, $args);

        // handle newly created data
        // ---------------------------------------------------------------------

        // if faulty data, return false
        if ($data == false) {

            if ($cache_errors && !$do_not_cache)   {   // cache the error
                $cache->set($key, '_ERROR_', $ttl);
            }

            # if (!PRODUCTION)   Benchmark::add_spot('proxy-new-error');
            return false;
        }

        // store to cache
        if (!$do_not_cache) {
            $cache->set($key, $data, $ttl);
        }

        # if (!PRODUCTION)   Benchmark::add_spot('proxy-new');
        return $data;   // serve data
    }

}
