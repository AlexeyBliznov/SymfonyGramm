<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id, ORM\Column(type: 'integer', unique: true)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;
    #[ORM\Column(type: 'user_user_email', unique: true, nullable: true)]
    private Email|null $email;
    #[ORM\Column(name: 'password_hash', type: 'string', nullable: true)]
    private string $passwordHash;
    #[ORM\Column(name: 'confirm_token', type: 'string', nullable: true)]
    private string|null $confirmToken;
    #[ORM\Column(type: 'user_user_status')]
    private Status $status;
    #[ORM\Column(type:'user_user_role')]
    private Role $role;
    #[ORM\Embedded(class: ResetToken::class, columnPrefix: 'reset_token_')]
    private ResetToken|null $resetToken;
    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Account::class, cascade: ['persist'], orphanRemoval: true)]
    private Account|null $account;

    public function __construct()
    {
        $this->status = Status::new();
        $this->resetToken = null;
    }

    public function create(Email $email, string $passwordHash, string $confirmToken): void
    {
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->confirmToken = $confirmToken;
        $this->role = Role::user();
        $this->status = Status::wait();
    }

    public function confirmSignUp(): void
    {
        if ($this->status->isActive()) {
            throw new \DomainException('User is already confirmed');
        }

        $this->status = Status::active();
        $this->confirmToken = null;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function requestPasswordReset(ResetToken $token, \DateTimeImmutable $date): void
    {
        if (!$this->email) {
            throw new \DomainException('Email is not specified.');
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Resetting is already requested.');
        }
        $this->resetToken = $token;
    }

    public function passwordReset(\DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new \DomainException('Resetting is not requested');
        }
        if ($this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Reset token is expired');
        }

        $this->passwordHash = $hash;
        $this->resetToken = null;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new \DomainException('Role is already same.');
        }
        $this->role = $role;
    }

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return [$this->role->getName()];
    }

    public function eraseCredentials()
    {

    }

    public function getUserIdentifier(): string
    {
        return $this->email->getValue();
    }

    public function getPassword(): ?string
    {
        return $this->passwordHash;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }
}
