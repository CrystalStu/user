<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface {

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

    public function getVerificationCode() {
        return $this->verificationCode;
    }

    public function getAdmin() {
        return $this->admin;
    }

    public function getSession() {
        return $this->session;
    }

    public function getLastSent() {
        return $this->lastSent;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return $this->active;
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

    public function setVerificationCode($value) {
        $this->verificationCode = $value;
    }

    public function setSession($value) {
        $this->session = $value;
    }

    public function setLastSent($value) {
        $this->lastSent = $value;
    }

    public function eraseCredentials() {
        // TODO: Implement eraseCredentials() method.
    }

    public function getRoles() {
        return array('ROLE_USER');
    }

    public function getSalt() {
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
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Length(max=32)
     */
    private $verificationCode;

    /**
     * @ORM\Column(type="string", length=4096, nullable=true)
     * @Assert\Length(max=4096)
     */
    private $session;

    /**
     * @ORM\Column(type="string", length=4096, nullable=true)
     * @Assert\Length(max=4096)
     */
    private $lastSent;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $grp = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mGrp;

    public function getGrp(): ?array {
        return $this->grp;
    }

    public function setGrp(?array $grp): self {
        $this->grp = $grp;

        return $this;
    }

    public function getMGrp(): ?int {
        return $this->mGrp;
    }

    public function setMGrp(?int $mGrp): self {
        $this->mGrp = $mGrp;

        return $this;
    }
}
