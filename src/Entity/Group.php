<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="grp")
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 */
class Group
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $owner;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $admin = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $member = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOwner(): ?int
    {
        return $this->owner;
    }

    public function setOwner(?int $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getAdmin(): ?array
    {
        return $this->admin;
    }

    public function setAdmin(?array $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getMember(): ?array
    {
        return $this->member;
    }

    public function setMember(?array $member): self
    {
        $this->member = $member;

        return $this;
    }
}
