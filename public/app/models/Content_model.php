<?php 
namespace app\models;

use \Registry;


class Content_model {

    ##  P E T I T I O N S
    ## -------------------------------------------------------------------------

    /** add petition
     * 
     * add petition record to the database
     * 
     * @param array $data 
     * @return int new petition-ID
     */
    public static function add_petition($data)
    {
        return Registry::use('database')->query(
            "INSERT INTO petition
                (user_id, `type_id`, `subject`, `signature`, `form_structure`)
            VALUES (:user_id, :type_id, :subject, :signature, :form_structure)",
            [
                ':user_id' => $data['user_id'],
                ':type_id' => $data['type_id'],
                ':subject' => $data['subject'],
                ':signature' => $data['signature'],
                ':form_structure' => $data['form_structure']
            ]
        )->lastInsertID();
    }


    /** set protocol
     * 
     * @param int $pid: petition ID
     * @param string $protocol: official pritocol number (full string)
     */
    public static function set_protocol($pid, $protocol)
    {
        Registry::use('database')->runQuery(
            "UPDATE petition SET protocol = :protocol WHERE id = :pid",
            [
                ':protocol' => $protocol,
                ':pid' => $pid
            ]
        );
        return true;
    }


    /** user petitions
     * 
     * get all petitions of a user;
     * 
     * @param int $uid: user id
     * @return array petition records
     */
    public static function user_petitions($uid)
    {
        $list = Registry::use('database')->runQuery(
            "SELECT * FROM petition WHERE user_id = :uid",
            [ ':uid' => $uid ]
        );

        $results = [];      // re-format results
        foreach($list as $key => $rec) {
            $results[] = [
                'id' => $rec['id'],
                'type_id' => $rec['type_id'],
                'subject' => $rec['subject'],
                'signature' => $rec['signature'],
                'protocol' => $rec['protocol'] ?? '',
                'creation_date' => date("Y-m-d", strtotime($rec['creation_date'])),
                'form_structure' => unserialize($rec['form_structure'])
            ];
        }
        return $results;
    }


    /** all petitions
     * 
     * get all petitions of a user
     * 
     * @param void
     * @return array petition records
     */
    public static function all_petitions()
    {
        return Registry::use('database')->runQuery(
            "SELECT petition.*,
            CONCAT(user.last_name, ' ', user.first_name) as UserName
            FROM petition
            LEFT JOIN user ON user.id = petition.user_id",
            []
        );
    }


    public static function get_petition($filters)
    {
        $where = [];
        $binds = [];
        foreach($filters as $key => $val) {
            $where[] = $key ." = :". $key;
            $binds[':'.$key] = $val;
        }

        $rec = Registry::use('database')->query(
            "SELECT * FROM petition WHERE ". implode(" AND ", $where),
            $filters
        )->getFirst();

        if ($rec === false) return false;
        else {
            return [
                'id' => $rec['id'],
                'type' => (($rec['type_id'] == 1) ? 'application' : 'penalty'),
                'subject' => $rec['subject'],
                'signature' => $rec['signature'],
                'protocol' => $rec['protocol'] ?? false,
                'creation_date' => date("Y-m-d", strtotime($rec['creation_date'])),
                'form_structure' => unserialize($rec['form_structure'])
            ];
        }
    }



    ##  F I L E S
    ## -------------------------------------------------------------------------

    /** files
     * return all files
     * @param void
     * @return array
     */
    public static function files()
    {
        return Registry::use('database')->runQuery("SELECT * from media", []);
    }


}
