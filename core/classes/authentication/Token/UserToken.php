<?php
/** Class UserToken
 * 
 * @package DevCoder\Authentication
 * 
 */
namespace anom\authentication\Token;

use anom\authentication\Token\UserTokenInterface;
use anom\authentication\UserInterface;

class UserToken implements UserTokenInterface
{
    /**
     * @var UserInterface
     */
    private $user;


    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }


    /** getUser
     * 
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }


    /** serialize()
     * 
     * serializes the user structure (with user's property values)
     * ... then it will be saved into session[DEFAULT_PREFIX_KEY]
     *
     */
    public function serialize(): string
    {
        return serialize($this);
    }
}
