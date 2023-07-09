<?php

// class DatabaseSession implements Session_interface
class DatabaseSession implements SessionHandlerInterface
{
    /** PROPERTIES
     * -------------------------------------------------------------------------
     */

    private $db;    // the database object

    // private $db_driver;    // the database driver


    /** METHODS
     * -------------------------------------------------------------------------
     */

    /** __construct
     * set database connection
     * set session handler to overide default session
     * start session
     */
    public function __construct()
    {
        // Prepare the Database object
        $this->db = Registry::use('database');

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
        session_name(SESSION_NAME);
        session_start();
    }

    // public function open(string $path, string $name) : bool
    // NOTE: $path and $name are unsued
    public function open() : bool
    {
        if ($this->db) {
            return true;
        }
        else {
            // database connection may be closed from another class
            // (ex. from some user class); in this case...
            // create a new database connection, then recheck.
            $conn = new Database();
            // $this->db = $conn->connect();

            if ($conn) {
                $this->db = $conn;
                return true;
            }
        }
        return false;
    }


    public function close() : bool
    {
        // DEPRECATED: the database object is shared (don;t clode it!)
        // // Close the database connection
        // if ($this->db = null) { return true; }
        // else return false;

        return true;
    }


    public function read(string $id) : string|false
    {
        $exist = $this->db->runQuery(
            "SELECT data FROM sessions WHERE id = :id",
            [':id', $id]
        );

        if (($exist === false) || (count($exist) == 0)) {
            return false;

        } else {
            $data = $exist[0];
        }

        if (is_null($row['data'])) {
            return '';
        }
        return $row['data'];
    }


    public function write(string $id, string $data) : bool
    {
        // Create timestamp
        $access = time();
        $check = $this->db->runQuery(
            "REPLACE INTO sessions VALUES (:id, :access, :data)",
            [':id' => $id, ':access' => $access, ':data' => $data ]
        );
        return ($check == false) ? false : true;
    }


    public function destroy(string $sassionID) : bool
    {
        $check = $this->db->runQuery(
            'DELETE FROM sessions WHERE id = :id',
            [':id' => $sassionID]
        );
        return ($check == false) ? false : true;
    }


    public function gc(int $max)
    {
        // Calculate what is to be deemed old
        $old = time() - $max;
        $check = $this->db->runQuery(
            'DELETE FROM sessions WHERE access < :old',
            [':old' => $old]
        );
        return ($check == false) ? false : 1;
        // check garbage-collector probability to run
        // echo "probability: ". ini_get("session.gc_probability") ." / ". ini_get("session.gc_divisor") . ", ttl: ". ini_get("session.gc_maxlifetime"); die();

    }

    # callable $create_sid = ?,
    # callable $validate_sid = ?,
    # callable $update_timestamp = ?
}
