<?php
/** Class UserManager
 * 
 * @package DevCoder\Authentication\Core
 * 
 */
namespace anom\authentication\Core;

use anom\authentication\Core\UserManagerInterface;
use anom\authentication\Token\UserToken;
use anom\authentication\Token\UserTokenInterface;
use anom\authentication\UserInterface;

class UserManager implements UserManagerInterface
{

    use PasswordTrait;


    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    /** getUserToken()
     * == get user from session
     * 
     */
    public function getUserToken(): ?UserTokenInterface
    {
        $userToken = null;
        if ($this->hasUserToken()) {
            $userToken = unserialize($_SESSION[UserTokenInterface::DEFAULT_PREFIX_KEY]);
        }

        return $userToken;
    }


    /** hasUserToken()
     * == is user loged in
     * 
     * @param void
     * @return boolean
     */
    public function hasUserToken(): bool
    {
        $key = UserTokenInterface::DEFAULT_PREFIX_KEY;
        return (array_key_exists($key, $_SESSION) && unserialize($_SESSION[$key]) !== false);
    }


    /** isGranted
     * 
     * checks if user is granded some role(s)
     * from the array of roles that are passed
     * 
     * @example:
     * $token->isGranted(['editor', 'designer']) ...
     * returns true if the user is editor or designer (or both)
     * 
     * @param $roles (array) : array of (int) role IDs
     * @return boolean
     */
    public function isGranted(array $roles): bool
    {
        // if (!is_null($userToken = $this->getUserToken())) {
        if (is_null($userToken = $this->getUserToken())) {
            return false;
        }

        if ($userToken->getUser() instanceof UserInterface) {
            return (!empty(array_intersect($roles, $userToken->getUser()->getRoles())));
        }

        return false;
    }


    /** createUserToken()
     * == serializes user and stores it into session
     * so $_SESSION[DEFAULT_PREFIX_KEY] has the serialized representaion of user
     */
    public function createUserToken(UserInterface $user): UserTokenInterface
    {
        $userToken = new UserToken($user);
        $_SESSION[UserTokenInterface::DEFAULT_PREFIX_KEY] = $userToken->serialize();

        return $userToken;
    }


    /** logout
     * == clear session
     * 
     */
    public function logout(): void
    {
        if ($this->hasUserToken()) {
            unset($_SESSION[UserTokenInterface::DEFAULT_PREFIX_KEY]);
        }
    }

}
