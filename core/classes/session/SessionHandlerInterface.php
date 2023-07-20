<?php
/** SessionHandlerInterface
 * 
 * as of php's documentation (check manual)
 * https://www.php.net/manual/en/class.sessionhandlerinterface.php
 */
namespace anom\core\session;

interface SessionHandlerInterface
{

    /* Methods
     *--------------------------------------------------------------------------
     */

    public function open(string $path, string $name) : bool;

    public function close() : bool;

    public function read(string $id) : string|false;

    public function write(string $id, string $data) : bool;

    public function destroy(string $id) : bool;
    
    public function gc(int $max_lifetime) : int|false;
    
}

/** TODO: (thoughts)
 * ... for a brand-new session implementation
 * 
 * In most cases, the session data does not require persistence.
 * The session information could be cached to improve performance
 * 
 * So (in theory) we could create a two-dimension session mechanism
 * 
 * + a fast dimension
 * : could be file-based (using local FS [php's default?] or redis/memcached)
 * 
 * + a shared dimension
 * : implemented in a shared database
 * 
 * Posible algo:
 * ... (check later on this comment-block: session life-cycle )
 * 
 * open  : 
 * close : on both
 * read  : check Local; if not exist check database; set session_id($id)
 * write : on both
 * destroy : on both
 * gc    : on server
 * 
 * when regenerating session_id, keep database informed about the new session_id
 * when closing, keep database informed about the session end-of-life.
 * 
 * a session timeout on the local leg will trigger a confirm-session-from-db
 * 
 * garbage collector on the db should remove expired sessions of some X seconds
 * and earier (X needs to be determined by practice/expirience/tries)
 * 
 * (+) in order to keep tracking of the session between multiple servers through
 * the (shared) database, a unique secret-between-the-servers id can be used
 * 
 * so...
 * 
 * #1
 * -> someone visits website through server A
 * -> server A creates a new session-id for the visitor and a shared-session-id
 * -> saves both into database
 * 
 * later...
 * 
 * #2
 * -> the same device is connected (via loadbalancer) into server B
 * -> server B don't have the session-id (send by the client) but gets it from db
 *   (is session-id do not exist on the db, then this is a new session)
 * 
 * = now both server A and B have the same session-id localy
 * 
 * later...
 * 
 * #3
 * -> some server (let's say B) regenerates the visitors session-id
 * -> if later the visitor falls into server A, the A will retrieve the new
 *   session id through the #2 scenario
 * 
 * this way 
 * + any server can update/regenerate the visitor's session-id
 * + the visitor can be served from both servers randomly
 * + all session variables exist on the database (always)
 * + all session variables exist on all servers synced-on-demand
 * 
 * 
 * stucture of session:
 * ---
 * - shared-session-id: (secret + persistent); exposed between servers **primary
 * - session-id: (may change/regenerate/update); exposed to the client **indexed
 * - data: serialized array
 * - creation_timestamp:
 * - last_touched_timestamp:
 * - expiration_timestamp: should point to the future or session has expired
 * - csrf_token:
 * - JWT_token
 * - AES-key (this way private data can be kept in browser)
 * 
 * 
 * what will kept on browser/client (via cookie)
 * ---
 * - session-name => session-id
 * - user info => AES_ectypted(serialized[t=>csfr_token, u=> user_id, s=>SIGNATURE])
 *
 * 
 * Writable file-system shall change
 * (local writable file-system tree)
 * ---
 * /html/storage
 *          |
 *          |-- cache   : query-caches
 *          |
 *          `-- session : FS\sessions
 *               |
 *                `-- indexes : share-session-id indexes
 * 
 * 
 * 
 * NOTE: Session life-cycle
 * 
 * session_start() * firsts time
 * ---
 * ::open(path,PHPSESSID) -> (session_id not exist) -> false
 * ::create_sid -> '123def'
 * ::read('123def') ?-or/and- ::close()
 * 
 * 
 * session_start() * next times
 * ---
 * ::open(path, PHPSESSID) -> (session_id exist) -> true
 * ::read('123def') -> return data -> will fill $_SESSION[*]
 * 
 * 
 * $_SESSION['foo'] = 'bar';
 * ---
 * ::write('123def', 'foo|s:3:"bar";') -> ['foo' => "bar"]
 * ::close()
 * 
 * 
 * session_regenerate_id();
 * ---
 * ::create_sid() -> def123
 * 
 * 
 * session_reset()
 * ---
 * ::open()
 * ::read('def123')
 * 
 * 
 * session_write_close()
 * ---
 * ::write('123def', 'foo|s:3:"bar";')
 * ::close()
 * 
 * 
 * session_destroy()
 * ---
 * ::destroy('def123')
 * ::close()
 *
 */
