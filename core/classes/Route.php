<?php
/** Route
 * ---
 * Methods:
 * - add( expression , callback_function , method )
 * - notFound( callback_function )
 * - methodNotAllowed( callback_function )
 * - run()
 *
 * + matches static paths
 * + matches dynamic paths through regex expressions
 * + takes care of request-method
 */
namespace anom\core;

class Route {

    private static $routes = Array();       // array to host routes
    private static $notFound = null;        // 404 error function
    private static $methodNotAllowed = null;    // 404 error function

    /** add (route)
     * ---
     * @param string $expression : static or regex matcher
     * @param callback $function : function to be executed if expression is matched
     * @param string $method : get / post / any
     */
    public static function add( $expression, $function, $method = 'get' )
    {
        array_push(self::$routes, Array(
            'expression' => $expression,
            'function' => $function,
            'method' => strtolower($method)
        ));
    }


    /** notFound($function)
     * ---
     * @param callback $function : callback function to be executed
     */
    public static function notFound($function)
    {
        self::$notFound = $function;
    }


    /** run()
     * ---
     * Parse request ; Find mathing route ;
     * then call route's function
     * usualy a Controller::method([poarametres])
     */
    public static function run(Request $request)
    {
        // $request = Registry::get('REQUEST');
        $path   = $request->PATH;       // request path
        $method = $request->METHOD;     // request method

        $path_match_found = false;
        $route_match_found = false;

        foreach(self::$routes as $route) {

            // If method matched check the path
            if ($route['method'] == $method || $method == 'any') {

                // Add 'find string start' automatically
                $route['expression'] = '^'.$route['expression'];

                // Add 'find string end' automatically
                $route['expression'] = $route['expression'].'$';

                // Check path match
                if (preg_match('#'. $route['expression'] .'#', $path, $matches)) {

                    $route_match_found = true;

                    array_shift($matches);  // Always remove first element. This contains the whole string

                    call_user_func_array($route['function'], $matches);
                    break;  // Do not check other routes

                }
            }
        }

        // No matching route was found
        if (!$route_match_found) {
            header("HTTP/1.0 404 Not Found");
            if (self::$notFound) {
                call_user_func_array(self::$notFound, []);
            }
        }

    }

}
