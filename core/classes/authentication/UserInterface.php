<?php
/**
 * Interface UserInterface
 * 
 * @package DevCoder\Authentication
 * 
 */
# namespace DevCoder\Authentication;

interface UserInterface
{
    public function getUsername() :?string;

    public function getPassword() :?string;

    public function getRoles() : array;

    public function isEnabled(): bool;
}