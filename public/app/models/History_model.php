<?php
namespace app\models;

use \Registry;


class History_model
{

    /** track user access
     * 
     */
    public static function trackUserAccess($user_id, $type, $message, $note='_')
    {

        Registry::use('database')->query(
            "INSERT INTO history
                (user_id, `type`, `message`, `note`, `ip`)
            VALUES
                (:uid, :type, :msg, :note, :ip)",
            [
                ':uid' => $user_id,
                ':type' => $type,
                ':msg' => $message,
                ':note' => $note,
                ':ip' => Registry::get('REQUEST')->IP
            ]
        )->lastInsertID();
        
    }



}