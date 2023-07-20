<?php 
namespace app\extends;

use anom\authentication\Core\UserManager;
use anom\core\Registry;


/** ClassUserManager
 * 
 * extends (anom's) UserManager
 * 
 * this way it specializes the UserManagement
 * according to this app's special rules
 * 
 */
class App_manager extends UserManager
{

    private $index_key = 'email';    // identifing user field (username, email, etc )

    protected $user;


    /** constructor
     * --- -- -- - - -
     */
    public function __construct()
    {
        parent::__construct();
    }


    /** register user
     * 
     * register into database;
     * send OTP for account confirmation
     * 
     * @param (void) : user properties passed via REQUEST->POST
     * 
     */
    public function register_user()
    {
        $req = Registry::get('REQUEST');
        $register = Access_model::registerUser($req->POST);

        if ($register['success']) {
            
            //create OTP;
            $otp = $this->create_OPT($register['id']);
            
            // send for account confirmation


        } else {
            print_r($register);
        }

    }


//    /** createUserToken()
//     * == serializes user and stores it into session
//     * so $_SESSION[DEFAULT_PREFIX_KEY] has the serialized representaion of user
//     */
//    public function createUserToken(UserInterface $user): UserTokenInterface
//    {
//        $userToken = new UserToken($user);
//        $_SESSION[UserTokenInterface::DEFAULT_PREFIX_KEY] = $userToken->serialize();
//
//        return $userToken;
//    }

    /** ??
     * 
     */
    public function login_user()
    {
        
    }


    /** forgot password
     * 
     * send an otp
     * (then verify)
     * 
     */
    public function forgot_password()
    {

    }


    /** create OTP
     * 
     * create a random OTP
     * keep it into User's database Table
     * 
     * @param $id (int): user id
     * 
     */
    public function create_OTP($id)
    {
        $otp = rand(100000,999999);
        Access_model::set_OTP($id, $otp);

        return true;
    }


    /** Verify Account
     * 
     * check if posted OTP matched the one sent to the user
     * 
     * @param $identity (array): pair of [index_key => identity_value]
     *      example: [ 'email' => 'geo@roptron.gr' ]
     * 
     */
    public function verify_otp($identity, $otp)
    {

    }


}