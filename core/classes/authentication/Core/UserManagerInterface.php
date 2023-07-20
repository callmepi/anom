<?php
/** Interface UserManagerInterface
 * 
 * @package DevCoder\Authentication\Core
 * 
 */
namespace anom\authentication\Core;

use anom\authentication\Token\UserTokenInterface;
use anom\authentication\UserInterface;

interface UserManagerInterface
{
    public function getUserToken() : ?UserTokenInterface;

    public function hasUserToken() : bool;

    public function createUserToken(UserInterface $user): UserTokenInterface;

    public function logout() : void;

    public function cryptPassword(string $plainPassword) : string;

    public function isPasswordValid(UserInterface $user, string $plainPassword) : bool;
}
