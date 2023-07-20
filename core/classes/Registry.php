<?php

/** Registry
 * ---
 * 
 * Regisrty is a global repository of { Key : Value } pairs,
 * where Key is a friendly label, and...
 * Value can be almost anything (number|string|array|object etc)
 *
 * Usually it has a central role in the framework;
 * Can hold the Request instance and several other core instanses
 * like database connections, cache services,  etc.
 * 
 * Designwise, Registry is a Singleton class
 * implementing (both) the Singleton and the Facade design pattern
 * allowing easy binding of core-singleton interfaces and objects
 * into the application
 * 
 * ---
 */
namespace anom\core;

class Registry
{

    ## STATIC PROPERTIES
    ## -------------------------------------------------------------------------

    /** @var array $records
     * keeps all ready-to-use records
     * --- --- -- - - -
     * 
     * These are set-records [defined via Registry::set()],
     * or
     * called vow-records [called via Registry::use()]
     * 
     * NOTE:
     * $records does not include the vows that have not carried-out yet
     */
    private static $records = Array();

    /** @var array $wish
     * keeps all records that will be carried-out later (if ever) 
     * --- -- -- - - -
     * 
     * These are registered via Registry::vow()
     */
    private static $wish = Array();



    ## METHODS
    ## -------------------------------------------------------------------------

    /** set
     * --- -- -- - - -
     * store $data to the registry undel the label $key
     * 
     * @param string $key
     * @param mixed $data
     */
    static function set($key, $data)
    {
        // TODO: not use if needed...
        // you may include an explicit method for reseting the key
        // or even reVOWing a wish;
        // or it could be just a 3rd paremeter like
        // function set($key, $data, $reset = false) {...}
        // function set($key, $function, $reset = false) {...}

        if (!isset(self::$records[$key])) {

            self::$records[$key] = $data;
            return true;

        } else { return fasle; }
    }


    /** get
     * --- -- - - -
     * get data from registry,
     * stored under the label $key
     * 
     * @param string $key : the label
     * @return mixed data
     */
    static function get($key)
    {
        // if defined and not beeing a vow/wish, serve
        if (isset(self::$records[$key]) && (!isset(self::$wish[$key]))) {

            return self::$records[$key];

        } else { return false; }
    }


    /** vow
     * --- -- -- - - -
     * implements a promise of a function operation
     * when/if the registry label is called;
     * it can store a function, an object handler etc.
     * 
     * @example:
     * Registry::vow('key', function(){ return new Obj(); })
     * 
     * The main advantage of using a 'vow' vs a 'set' is
     * that a 'vow' will not use system resources to prepare
     * the data/object/handler/etc until/if-ever is called.
     * 
     * @param string $label : label / refference key
     * @param callback $function : 
     */
    static function vow($label, $function)
    {
        self::$wish[$label] = $function;
    }

    
    /** use (some vow)
     * ---
     * this is the method that carries out
     * the promissed function (with arguments)
     * 
     * @param string $label
     * @param array $args
     */
    static function use($label, $args=[])
    {
        // if already prepered (and is a wish) serve it
        if (isset(self::$records[$label]) && isset(self::$wish[$label])) {

            return self::$records[$label];

        } else {
            
            // otherwise if is a wish/promise
            if (array_key_exists($label, self::$wish)) {

                // prepare, store and serve it
                $carryOut = call_user_func_array(self::$wish[$label], $args);
                self::$records[$label] = $carryOut;

                return $carryOut;

            } else { return false; }
        }
    }

}
