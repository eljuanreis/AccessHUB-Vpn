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
    private string $username;

    #[ORM\Column(type: "string", length: 255)]
    private string $password;

    // Novo campo: número de tentativas de login
    #[ORM\Column(type: "integer")]
    private int $loginAttempts = 0;

    public function getId(): ?int { return $this->id; }

    public function getUsername(): string { return $this->username; }

    public function setUsername(string $username): self { 
        $this->username = $username; 
        return $this; 
    }

    public function getPassword(): string { return $this->password; }

    public function setPassword(string $password): self { 
        $this->password = $password; 
        return $this; 
    }

    // Getter para loginAttempts
    public function getLoginAttempts(): int {
        return $this->loginAttempts;
    }

    // Setter para loginAttempts
    public function setLoginAttempts(int $attempts): self {
        $this->loginAttempts = $attempts;
        return $this;
    }

    // Incrementador opcional
    public function incrementLoginAttempts(): self {
        $this->loginAttempts++;
        return $this;
    }

    // Reset opcional
    public function resetLoginAttempts(): self {
        $this->loginAttempts = 0;
        return $this;
    }
}
