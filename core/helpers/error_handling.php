<?php
/** ERROR HANDLING SECTION
 * handles exceptions and errors (even fatal)
 * -----------------------------------------------------------------------------
 * The code...
 * + Catches and handles all Errors and Throwable exceptions;
 * - creates the error messages (for the requester and developer) 
 * ? TODO: routes the error messages to the logging channels
 */


// Global array to save errors
$anom_ERRORS = [];  // ok, Globals are not recommended, but...
    // this one collects only errors (about the application);
    // it is oriented for the developer; not for the request.


/** Error handler,
 * passes flow over the exception logger with new ErrorException.
 */
function handle_error(int $type, string $message, string $file, int $line)
{
    handle_exception( new ErrorException($message, 0, $type, $file, $line) );
}


/** Uncaught exception handler.
*/
function handle_exception(Throwable $e)
{
    global $anom_ERRORS;
    
    // full message description to be logged
    $message = get_class($e) .": ". $e->getMessage()
        . " on file '". $e->getFile() . "' at line {$e->getLine()}";

    $anom_ERRORS[] = $message;      // store the error
}


/**
 * Catch fatal error work-around
 * (set_error_handler not working on fatal errors).
 */
function on_shutdown_check_for_fatal()
{
    global $anom_ERRORS;

    $error = error_get_last();

    if ($error != null) {       // ignore 'normal' system shutdowns [exit|die]() 

        if ( $error["type"] == E_ERROR ) {      // get as much error-info
            handle_error(
                $error["type"] ?? -1,
                $error["message"] ?? 'unknown error',
                $error["file"] ?? 'unknown file',
                $error["line"] ?? -1
            );
        }
    }
    
    if (($anom_ERRORS != [])) {

        // TODO:
        // Implemetnig the design/layout of the error-messages
        // output should not be an error's-handling task, thus
        // shall be assigned to the Rendes/View level;
        // Same about the log service channels

        // TODO:
        // FORWARD ERRORS into LOG-SYSTEM
        // ex. into some file...
        // file_put_contents("logs/thowable.log", $message_template .PHP_EOL, FILE_APPEND);
        // or ...some other channer (Email, Slack, Database etc).
        // Channes can be decided according to error severity;
        // a file-system error loggin for backup is recommended
        // partitioniong of errors onto file-system can be useful

        // TODO:
        // 1st: Log Errors
        // 2nd: Show Errors

        if (PRODUCTION) {
            if (!defined('OUTPUT_STARTED')) {
                // throw a prety-error message
                Render::view('error/general', ['message' => 'Please forgive me, I know not what I do']);
            }

        } else {

            if (!defined('OUTPUT_STARTED')) {
                // no template loaded; render erros in general-error template
                Render::view('error/general', ['message' => implode("<br/>And:<br/>", $anom_ERRORS)]);

            } else {
                 echo '<div style="padding:1em;background:#933;width:100%;color:#fff">
                    Errors:<br />'. implode("<br/>And:<br/>", $anom_ERRORS) .'</div>';
            }
        }
    }
}


// register callback on shutdown
register_shutdown_function( "on_shutdown_check_for_fatal" );

// register custom error handler callback
set_error_handler( "handle_error" );

// set custom exception handler function
set_exception_handler( "handle_exception" );

// send errors to stderr (instead of stdout)
ini_set( "display_errors", "off" );

// thow out all errors
error_reporting( E_ALL );

