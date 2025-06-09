<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 100)]
    private string $name;

    #[ORM\Column(type: "string", length: 30, unique: true)]
    private string $username;

    #[ORM\Column(type: "string", length: 255)]
    private string $password;

    #[ORM\Column(type: "integer")]
    private int $loginAttempts = 0;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: "boolean")]
    private bool $isAdmin = false;

    #[ORM\Column(type: "boolean")]
    private bool $active = true;

    #[ORM\Column(type: "string", length: 64, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $resetTokenCreatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getLoginAttempts(): int
    {
        return $this->loginAttempts;
    }

    public function setLoginAttempts(int $attempts): self
    {
        $this->loginAttempts = $attempts;
        return $this;
    }

    public function incrementLoginAttempts(): self
    {
        $this->loginAttempts++;
        return $this;
    }

    public function resetLoginAttempts(): self
    {
        $this->loginAttempts = 0;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    // Novo campo email
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function active(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    public function getResetTokenCreatedAt(): ?\DateTimeInterface
    {
        return $this->resetTokenCreatedAt;
    }

    public function setResetTokenCreatedAt(?\DateTimeInterface $resetTokenCreatedAt): self
    {
        $this->resetTokenCreatedAt = $resetTokenCreatedAt;
        return $this;
    }
}
