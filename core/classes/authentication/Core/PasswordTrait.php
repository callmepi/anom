<?php
/** Trait PasswordTrait
 * 
 * @package DevCoder\Authentication\Core
 * 
 */
# namespace DevCoder\Authentication\Core;
# 
# use DevCoder\Authentication\UserInterface;

trait PasswordTrait
{
    
    private $cost = 10;     // Cost must be in the range of 4-31
            // a cost value in the range of 8-11
            // is a good balance between performance and security


    public function cryptPassword(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => $this->cost]);
    }


    public function isPasswordValid(UserInterface $user, string $plainPassword): bool
    {
        return password_verify($plainPassword, $user->getPassword());
    }


    public function setCost(int $cost): void
    {
        if ($cost < 4 || $cost > 31) {
            throw new \InvalidArgumentException('Cost must be in the range of 4-31.');
        }
        $this->cost = $cost;
    }
}
