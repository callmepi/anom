<?php

/** FileSessionHandler
 * is a custom File-based Session Handler
 * 
 * Could be useful when implemented along with data-cryptography,
 * otherwise php's default session handler (which is also file-based)
 * seems to be the obvious way to go;
 * 
 * NOTE:
 * if shared sessions accros an array of servers is needed,
 * a database-session handler is probably the best choice.
 * 
 * TODO:
 * implement cryptography
 */
namespace anom\core\session;

use anom\core\session\SessionHandlerInterface;

class FileSessionHandler implements SessionHandlerInterface
{
    /** The filesystem instance. */
    protected $sessionName;

    /** The path where all sessions should be stored. */
    protected $path;

    /** The number of minutes the session should be valid. */
    protected $minutes;

    /**
     * Create a new file driven session-handler instance.
     *
     * @param  string  $path
     * @param  int  $minutes
     * @return void
     */
    public function __construct()
    {
        // values comming from configuration
        $this->sessionName = SESSION_NAME;
        $this->sess_filename = FILESESSION_PATH;
        $this->minutes = $minutes;

        // Set handler to overide SESSION
        session_set_save_handler(
            array($this, "open"),
            array($this, "close"),
            array($this, "read"),
            array($this, "write"),
            array($this, "destroy"),
            array($this, "gc")
        );

        // Start the session
        session_name($this->sessionName);
        session_start();
    }

    /** open
     * set filename;
     * no need to touch the filesystem yet
     * return true (always)
     */
    public function open($savePath, $sessionName): bool
    {
        $this->sess_filename = $this->$path .'/'. $sessionName;
        return true;
    }

    /** close
     * nothing needs to be closed;
     * return true (always)
     */
    public function close(): bool
    {
        return true;
    }

    /** read
     * chech if file exists; if not return false
     * read data
     */
    public function read($sessionId): string|false
    {
        if (!file_exists($filename) || !is_readable($filename)) return false;
        $data = file_get_contents($filename);

        return @unserialize($data);
    }

    /** write
     * data serielized already by php's internal session engine
     */
    public function write($sessionId, $data): bool
    {
        $h = fopen($filename, 'w');
        if (fwrite($h, serialize($data)) === false) {
            throw new Exception('Could not write session data');
            return false;
        }
        fclose($h);

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function destroy($sassionID): bool
    {
        unlink($this->sassionID);

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return int
     */
    public function gc($lifetime) : int
    {
        $files = Finder::create()
                    ->in($this->path)
                    ->files()
                    ->ignoreDotFiles(true)
                    ->date('<= now - '. $lifetime .' seconds');

        $deletedSessions = 0;

        foreach ($files as $file) {
            $this->files->delete($file->getRealPath());
            $deletedSessions++;
        }

        return $deletedSessions;
    }
}
