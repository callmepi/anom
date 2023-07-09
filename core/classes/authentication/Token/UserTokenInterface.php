<?php
/** Interface UserTokenInterface
 * 
 * @package DevCoder\Authentication\Token
 * 
 */
# namespace DevCoder\Authentication\Token;
# 
# use DevCoder\Authentication\UserInterface;

interface UserTokenInterface
{
    const DEFAULT_PREFIX_KEY = 'user_security';

    public function getUser(): UserInterface;

    public function serialize(): string;
}
