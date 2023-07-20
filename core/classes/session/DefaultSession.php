<?php
/** DefauleSession
 * --
 * 
 * is a dummy session hanlder that wraps the
 * php's default session engine implementation;
 * 
 */
namespace anom\core\session;

use anom\core\session\SessionHandlerInterface;

class DefaultSession implements SessionHandlerInterface {

    public function __construct()
    {
        // Start the session
        // (the default way)
        // and let the session* functions 
        // do what they know
        ini_set( "session.gc_maxlifetime", MAX_SESSION_LIFE );
        session_set_cookie_params([
            'path' => '/',
            // 'domain' => COOKIE_DOMAIN,   # on localhost must be omitted entirely
            'secure' => COOKIE_SECURE,
            'samesite' => COOKIE_SAMESITE
        ]);
        session_name(SESSION_NAME);
        session_start();
    }

    #[\ReturnTypeWillChange]
    public function open(string $path, string $name) {}

    #[ReturnTypeWillChange]
    public function close() {}

    #[ReturnTypeWillChange]
    public function read(string $id) {}

    #[ReturnTypeWillChange]
    public function write(string $id, string $data) {}

    #[ReturnTypeWillChange]
    public function destroy(string $id) {}

    #[ReturnTypeWillChange]
    public function gc(int $max_lifetime) {}

}