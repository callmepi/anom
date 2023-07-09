<?php
/**
 * Class User
 * 
 * based on
 * @package DevCoder\Authentication
 * 
 */
# namespace DevCoder\Authentication;

class User implements UserInterface
{

    /** PROPERTIES
     * -------------------------------------------------------------------------
     */

    // @var string
    private $userName;

    // @var string
    private $password;

    // @var array
    private $roles = [];

    // @var bool
    private $enabled = true;



    /** GETs
     * -------------------------------------------------------------------------
     */

    /** getUserName
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->userName;
    }

    /** getPassword
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /** getRoles
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /** isEnabled
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }


    /** SETs
     * -------------------------------------------------------------------------
     */

    /** setUserName()
     * 
     * @param string $userName
     * @return User
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    /** setPassword()
     * 
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /** setRoles()
     * 
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /** setEnabled
     * 
     * @param bool $enabled
     * @return User
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }
}
