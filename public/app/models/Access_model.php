<?php

namespace app\models;

use \Registry;
use app\extends\App_manager;
use app\models\History_model;

class Access_model
{

    ## User methods
    ## -------------------------------------------------------------------------

    /** check user (by index key = Email)
     * 
     * @param $indexKey (string) : key for user identification
     * @param $value (string)
     * 
     * @param $email (string): user's email
     */
    public static function checkUser($email)
    {
        $user = Registry::use('database')->query(
            "SELECT * FROM user
            WHERE email = :email AND active = 1 AND deprecated IS NULL",
            [ ':email' => $email ]
        )->getFirst();
       
        // if no user, return false
        if ($user === false)     return false;
       
        return $user;
    }


    /** get user by id
     * 
     */
    public static function getUser_byID($uid)
    {
        $user = Registry::use('database')->query(
            "SELECT * FROM user
            WHERE id = :uid AND active = 1 AND deprecated IS NULL",
            [ ':uid' => $uid ]
        )->getFirst();
       
        // if no user, return false
        if ($user === false)     return false;
       
        return $user;
    }

    /** getUserByInvitation
     * 
     * @param string $invitation
     */
    public static function getUserByInvitation($invitation)
    {
        return Registry::use('database')->query(
            "SELECT * FROM user
            WHERE invitation = :invitation AND deprecated IS NULL",
            [ 'invitation' => $invitation ]
        )->getFirst();
    }


    /** create user
     * 
     * creates user record;
     * assigns privileged (usualy defaults);
     * creates activation_code
     * 
     * @param $data (array): Request->POST array
     * @param $password (string): secure hashed password
     * 
     * @return $activation_code
     * 
     */
    public static function registerUser($data, $password, $privileges = DEFAULT_PRIVILEGES)
    {
        $required_fields = [
            'name',
            'surname',
            'email',
            'password'
        ];

        // check required fields
        $isOK = true;
        foreach($required_fields as $fi) {
            if (empty($data[$fi]))    $isOK = false;
        }
        // if empty required fields exists ... return false
        if (!$isOK) {
            return [ "success" => false, 'error' => EMPTY_REQUIRED_FIELDS ];
        }

        // create an activation code
        $activation_code = md5($data['email'].time().rand(0, 10000));

        // if isOK go on and...
        // create user record
        $new_user_id = Registry::use('database')->query(
            "INSERT INTO user
                (`first_name`, `last_name`, `email`, `password`, `active`, `activation`)
            VALUES
                (:nam, :surname, :email, :pass, :act, :actcode)",
            [ 
                ':nam' => $data['name'],
                ':surname' => $data['surname'],
                ':email' => $data['email'],
                ':pass' => $password,
                ':act' => 0,      // needs email confirmation to be activated ...
                ':actcode' => $activation_code    // ... with the activation code
            ]
        )->lastInsertID();

        // set default privileges
        self::set_user_privileges($new_user_id, $privileges);

        // set reader role
        self::set_user_role($new_user_id, 5);

        // update history
        History_model::trackUserAccess($new_user_id, TRACK_ACCOUNT, 'Create User Account');

        // return success and user id
		return [
            "success" => true,
            'id' => $new_user_id ,
            'activation' => $activation_code
        ];
    }


    /** activate_set_password
     * 
     * activate user and set password
     * 
     * @param array $post
     * @param string $password: hased-password
     */
    public static function activate_set_password($post, $password)
    {
        // Update and set activate = true
        $rowCount = Registry::use('database')->query(
            "UPDATE user
            SET first_name = :first_name,
                last_name = :last_name,
                email = :email,
                father_name = :father_name,
                registration_number = :registration_number,
                sector = :sector,
                belonging_school = :belonging_school,
                position = :position,
                phone = :phone,
                `password` = :password,
                invitation = NULL,
                active = :active
            WHERE id = :id AND invitation = :invitation",
            [
                ':first_name' => $post['first_name'],
                ':last_name' => $post['last_name'],
                ':email' => $post['email'],
                ':father_name' => $post['father_name'],
                ':registration_number' => $post['registration_number'],
                ':sector' => $post['sector'],
                ':belonging_school' => $post['belonging_school'],
                ':position' => $post['position'],
                ':phone' => $post['phone'],
                ':password' => $password,
                ':active' => 1,
                ':id' => $post['id'],
                ':invitation' => $post['invitation']
            ]
        )->rowCount();

        // update history
        History_model::trackUserAccess($post['id'], TRACK_ACCOUNT, 'User Account Activated');

        return $rowCount;
    }



    /** update_user
     * 
     * activate user and set password
     * 
     * @param array $post;
     * @param int $id: user id
     */
    public static function update_user($post, $uid)
    {
        // Update and set activate = true
        $rowCount = Registry::use('database')->query(
            "UPDATE user
            SET first_name = :first_name,
                last_name = :last_name,
                email = :email,
                father_name = :father_name,
                registration_number = :registration_number,
                sector = :sector,
                belonging_school = :belonging_school,
                position = :position,
                phone = :phone,
                invitation = NULL,
                active = :active
            WHERE id = :id",
            [
                ':first_name' => $post['first_name'],
                ':last_name' => $post['last_name'],
                ':email' => $post['email'],
                ':father_name' => $post['father_name'],
                ':registration_number' => $post['registration_number'],
                ':sector' => $post['sector'],
                ':belonging_school' => $post['belonging_school'],
                ':position' => $post['position'],
                ':phone' => $post['phone'],
                ':active' => 1,
                ':id' => $uid
            ]
        )->rowCount();

        // update history
        if ($rowCount != 0) {
            History_model::trackUserAccess(
                $uid, TRACK_ACCOUNT, 'User properties changed'
            );
        }

        return $rowCount;

    }


    /** update_password
     * 
     * (re)set user's password
     * 
     * @param int $id: user id
     * @param string $hashpass: hashed password
     */
    public static function update_password($uid, $hashpass)
    {
        // Update and set activate = true
        $rowCount = Registry::use('database')->query(
            "UPDATE user
            SET `password` = :hashpass
            WHERE id = :id",
            [
                ':hashpass' => $hashpass,
                ':id' => $uid
            ]
        )->rowCount();

        // update history
        if ($rowCount != 0) {
            History_model::trackUserAccess(
                $uid, TRACK_ACCOUNT, 'User password changed'
            );
        }

        return $rowCount;
    }

    /** deprecate user account
     * by @param int $uid: user-id
     */
    public static function deprecate_user($uid)
    {
        Registry::use('database')->runQuery(
            "UPDATE user
            SET active = :active,
                deprecated = :deprecated
            WHERE id = :id",
            [
                ':active' => 0,
                ':deprecated' => 1,
                ':id' => $uid
            ]
        );
        return true;
    }


    /** invite user
     * 
     * update user with invitation data
     * 
     * @param int $uid: user-id
     * @param hash $invitation: MD5 hash invitation
     * @param int expiration: expiration timestamp
     */
    public static function invite_user($uid, $invitation, $expiration)
    {
        Registry::use('database')->runQuery(
            "UPDATE user
            SET invitation = :inv,
                otp_expiration = :expiration
            WHERE id = :id",
            [
                ':inv' => $invitation,
                ':expiration' => $expiration,
                ':id' => $uid
            ]
        );
    }


    /** activate user account
     * by @param int $uid: user-id
     */
    public static function activate_user($uid)
    {
        Registry::use('database')->runQuery(
            "UPDATE user
            SET active = :active,
                deprecated = :deprecated
            WHERE id = :id",
            [
                ':active' => 1,
                ':deprecated' => NULL,
                ':id' => $uid
            ]
        );
        return true;
    }



    ## Set permission methods
    ## -------------------------------------------------------------------------

    /** set_user_role
     * 
     * user, role are (int) IDs
     */
    public static function set_user_role($user, $role)
    {

        Registry::use('database')->runQuery(
            "INSERT INTO user_role (user_id, role_id) VALUES (:user, :role)",
            [ ':user' => $user, ":role" => $role ]
        );

        return true;
    }


}
