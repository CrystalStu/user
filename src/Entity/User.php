<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    private function encryptPassword($value) {
        $value = md5($value);
        $value = sha1($value);
        return $value;
    }

    /*public function __construct($id)
    {
        $this->id = $id;
    }*/

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getActive() {
        return $this->active;
    }

    public function getAdmin() {
        return $this->admin;
    }

    public function getSession() {
        return $this->session;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->active;
    }

    public function setId($value) {
        $this->id = $value;
    }

    public function setUsername($value) {
        $this->username = $value;
    }

    public function setPlainPassword($value) {
        $this->plainPassword = $value;
    }

    public function setPassword($value) {
        $this->password = $value;
    }

    public function setEmail($value) {
        $this->email = $value;
    }

    public function setActive($boolean) {
        $this->active = $boolean;
    }

    public function setAdmin($value) {
        $this->admin = $value;
    }

    public function setSession($value) {
        $this->session = $value;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    // add your own fields

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=25)
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=4096)
     * @Assert\Length(max=4096)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=60)
     */
    private $email;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="integer")
     */
    private $admin;

    /**
     * @ORM\Column(type="string", length=4096, nullable=true)
     * @Assert\Length(max=4096)
     */
    private $session;
}
