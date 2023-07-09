<?php 
namespace app\extends;

use User;
use Registry;


/** Classroom_user
 * 
 * extends (anom's) User
 * 
 * this way it specializes the UserManagement
 * according to this app's special rules
 * 
 */
class App_user extends User
{

    /** user id 
     * @var int
     */
    private $id;

    /** sex (gender)
     * @var int (1 = male, 2 = female )
     */
    private $sex;

    /** user name
     * @var string : first_name +' '+ last_name
     */
    private $name;

    /** other properties 
     * to keep near-by for easy access
     * all @var string
     */
    private $prifix;
    private $first_name;
    private $last_name;
    private $father_name;
    private $registration_number;
    private $phone;
    private $email;
    private $sector;
    private $belonging_school;
    private $position;




    /** SETTERS AND GETTERS
     * for id, name, attributes
     * -------------------------------------------------------------------------
     */

    // id 
    // --- -- -- - - -

    /** getID
     * @return int
     */
    public function getID(): int
    {
        return $this->id;
    }

    /** setID()
     * 
     * @param int : user id
     * 
     * @return User
     */
    public function setID(int $id): self
    {
        $this->id = $id;
        return $this;
    }


    // sex
    // --- -- -- - - -

    /** getSex()
     * @return int
     */
    public function getSex(): int
    {
        return $this->sex;
    }


    /** setSex()
     * 
     * @param int : sex (id)
     * 
     * @return User
     */
    public function setSex(int $sex): self
    {
        $this->sex = $sex;
        return $this;
    }



    // name
    // --- -- -- - - -

    /** getName
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /** setFirstName()
     * 
     * @param string : user's full name
     * 
     * @return User
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    

    // all other properties
    // --- -- -- - - -

    /** set
     * general seter for all other properties
     * 
     * @param $prop (string): property name
     * @param $val (string): property value
     * @return this (object)
     */
    public function set(string $prop, string $val)
    {
        switch ($prop) {
            case 'prefix':      $this->prefix = $val;       return $this; break;
            case 'first_name':  $this->first_name = $val;   return $this; break;
            case 'last_name':   $this->last_name = $val;    return $this; break;
            case 'father_name': $this->father_name = $val;  return $this; break;
            case 'phone':       $this->phone = $val;        return $this; break;
            case 'email':       $this->email = $val;        return $this; break;
            case 'sector':      $this->sector = $val;       return $this; break;
            case 'position':    $this->position = $val;     return $this; break;
            case 'registration_number':
                $this->registration_number = $val;          return $this; break;
            case 'belonging_school':
                $this->belonging_school = $val;             return $this; break;

            default:
                return $this;
        }
    }


    /** get
     * general getter for all properties
     * 
     * @param $prop (string): property name
     * @return (string): property value (or null if propety not exists)
     */
    public function get(string $prop)
    {
        switch ($prop) {
            case 'prefix':      return $this->prefix;       break;
            case 'first_name':  return $this->first_name;   break;
            case 'last_name':   return $this->last_name;    break;
            case 'father_name': return $this->father_name;  break;
            case 'phone':       return $this->phone;        break;
            case 'email':       return $this->email;        break;
            case 'sector':      return $this->sector;       break;
            case 'position':    return $this->position;     break;
            case 'registration_number': return $this->registration_number; break;
            case 'belonging_school':    return $this->belonging_school; break;

            default:
                return null;
        }
    }
   

}