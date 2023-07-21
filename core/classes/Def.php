<?php
/** Def class
 * is used to define all constants needed by the framework;
 * 
 * The constants are accessible via namespaced reference
 * ex. $option = Def::PROXY_IGNORE_CACHE
 * 
 */
namespace app\core;

class Def
{

    /** PROXY_FLAGS
     * -----------------------------------------------------------------------------
     * These options represend altered/non-default behaviour
     * and will be resolved bitwise, so use powers of 2
     * -----------------------------------------------------------------------------
     */
    const PROXY_CACHE_ERRORS = 1;
    const PROXY_IGNORE_CACHE = 2;
    const PROXY_DO_NOT_CACHE = 4;


    /** BENCHMARK REPORT TYPES
     * -----------------------------------------------------------------------------
     */
    const BENCH_REPORT_ARRAY    = 1;    // report as array
    const BENCH_REPORT_JSON     = 2;    // report as json oblject
    const BENCH_REPORT_XML      = 3;    // report as json oblject
    const BENCH_REPORT_HTML     = 7;    // report as HTML code
    const BENCH_REPORT_CODE     = 8;    // report as CODE/text
    const BENCH_REPORT_COMMENT  = 9;    // report as HTML comment

}
