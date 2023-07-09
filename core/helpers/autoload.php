<?php
/** register class-autoloader
 * 
 * NOTE:
 * Composer is strongly recommended; it is a much better option
 * and takes care for many more than just autoloading classes;
 * 
 * This's a fair autoloader in case composer is not available;
 * supports single and namespaced classes.
 */
spl_autoload_register(
    function($className) {

        // in order to support common namespaced classes
        // replace '\' with '/' to get the path
        $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $className);

        // check all possible classPaths for class
        foreach(CLASSPATHS as $key => $basePath) {

            $file = WEB_ROOT . $basePath . $classPath .'.php';

            // if class-file exist, include it
            if (file_exists($file)) {
                require_once $file;
                break;
            }

        }

        // // Feel free to extend the fanctionality
        // // example:
        // // custom (exception) namespaced classes loader
        // $customNSclasses = array(
        //   '\\George\\Class' => 'path/to/George/Class',
        //   '\\Mary\\Class'   => 'path/to/Marias/Class',
        //   ...
        // );
        // if array_key_exists($className) {
        //   require_once $customNSclasses[$className];
        // }
    }
);
