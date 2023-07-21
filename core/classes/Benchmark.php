<?php
/** Benchmarn class
 * -----------------------------------------------------------------------------
 * 
 * Handy class to evaluate the apprication's performace
 * 
 * [*] calulates time difference between any two or more marked timespots;
 * [*] tracks memory usage for the marked timespots
 * [*] creates reports in various formats
 * 
 * TODO:
 * + create an XML report
 * + create an (optional) ascii graph ??
 * 
 * -----------------------------------------------------------------------------
 */
namespace anom\core;

use anom\core\Def;

class Benchmark
{

    /** PROPERTIES
     * -------------------------------------------------------------------------
     */


    /** @var array
     * array of microtimes in [key => val] pairs, where
     * + key (string): is a custom label used for refering
     * + val (float) : the microtime when the timespot was created
     */
    private static $timeSpots = Array();


    /** @var array
     * array of memory used in [key => val] pairs, where
     * + key (string): is a custom label used for refering (same as in $timeSpots)
     * + val (float) : the memory usage (in bytes) when the timespot was created
     */
    private static $memoryUse = Array();


    /** @var boolean
     * flag to determin if reporting is enaabled
     */
    private static $reportEnabled = true;




    /** METHODS
     * -------------------------------------------------------------------------
     */

    
    /** add_timespot
     * ---
     * add new timespot
     * 
     * @param string $key : label of timespot
     */
    public static function add_spot(string $key)
    {
        self::$timeSpots[$key] = microtime(true);
        self::$memoryUse[$key] = round(memory_get_usage()/(1024*1024),2);
    }


    /** exist
     * ---
     * checks if a benchmark spot  with label $key exists 
     * 
     * @param string $key : label of timespot
     */
    public static function exist(string $key) : bool
    {
        return isset(self::$timeSpots[$key]);
    }


    /** desableReport
     * ---
     * desables report exporting
     */
    public static function disableReport()
    {
        self::$reportEnabled = false;
    }


    /** render_benchmark_report
     * ---
     * renders performance report
     * as html comment at the end of webpage in various modes
     * 
     * @param int $mode: report format id
     * ... default: Def::BENCH_REPORT_COMMENT (5) = report as html comment;
     * ... for more report-formats check the Def::BENCH_REPORT_* defines
     */
    public static function report(string $mode = Def::BENCH_REPORT_COMMENT)
    {
        if ( (self::$reportEnabled) && (!empty(self::$timeSpots)) ) {

            switch ($mode) {
                case Def::BENCH_REPORT_ARRAY:
                    return [
                        'timespots' => self::$timeSpots,
                        'memory_usage' => self::$memoryUse
                    ];
                    break;

                case Def::BENCH_REPORT_JSON:
                    return json_encode([
                        'timespots' => self::$timeSpots,
                        'memory_usage' => self::$memoryUse
                    ], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                    break;

                case Def::BENCH_REPORT_HTML:
                    $open = '<p>';
                    $close = '</b></p>';
                    $divider = ":<b>";
                    $prefix = '<div>';
                    $suffix = '</div>';
                    break;

                case Def::BENCH_REPORT_CODE:
                    $open = '';
                    $close = '';
                    $divider = ": ";
                    $prefix = '<pre>';
                    $suffix = '</pre>';
                    break;

                case Def::BENCH_REPORT_COMMENT:
                default:
                    $open = '<!--';
                    $close = '-->';
                    $divider = ":";
                    $prefix = '';
                    $suffix = '';
                    break;
            }

            $output = $prefix;

            // TIME REPORT 
            // ---------------------------------------------------------------------
            $output .= "\n\n{$open} Timings {$close}";

            // valid from php 7.3
            // $start = self::$timeSpots[ array_key_first(self::$timespots) ];
            // $end = self::$timeSpots[ array_key_last(self::$timespots) ];
            $start = self::$timeSpots['start'];
            $end = self::$timeSpots['end'];
            $all_dt = number_format($end - $start , 4)*1000 ." ms";

            

            // if more than 2 timespots
            // $output .= dt between each spot
            if (count(self::$timeSpots) > 2) {

                // calculate dt between spots
                $time_results = array();
                $prev_key = '';
                $prev_time = 0;
                foreach(self::$timeSpots as $key => $t) {
                    if (!$prev_time) {
                        $prev_time = $t;
                        $prev_key = $key;
                    }
                    else {
                        $dt = number_format($t - $prev_time , 4)*1000 ." ms";
                        $time_results[] = array(
                            'part' => "{$prev_key}[..{$key}]", ///$key,
                            'dt'   => $dt
                        );
                        $prev_key = $key; //// "{$prev_key}[..{$key}]";
                        $prev_time = $t;
                    }
                }

                // $output .= timings
                foreach ($time_results as $key => $val) {
                    $output .= "\n\t{$open} {$val['part']} {$divider} {$val['dt']} {$close}";
                }
            }

            // $output .= total time
            $output .= "\n\t{$open} total {$divider} {$all_dt} {$close}";


            // MEMORY REPORT
            // ---------------------------------------------------------------------
            $output .= "\n\n{$open} Memory Usage {$close}";

            if (count(self::$memoryUse) > 2) {

                foreach (self::$memoryUse as $key => $val) {
                    $output .= "\n\t{$open} {$key} {$divider} {$val}MB {$close}";
                }

            } else {
                $output .= "\n\t{$open} memory usage {$divider} ". round(memory_get_usage()/(1024*1024),2) ."MB {$close}";   
            }

            $output .= $suffix;

        } else { return; }

        return $output;
    }

}
