<?php
/** Interface UserTokenInterface
 * 
 * @package DevCoder\Authentication\Token
 * 
 */
namespace anom\authentication\Token;

use anom\authentication\UserInterface;

interface UserTokenInterface
{
    const DEFAULT_PREFIX_KEY = 'user_security';

    public function getUser(): UserInterface;

    public function serialize(): string;
}
