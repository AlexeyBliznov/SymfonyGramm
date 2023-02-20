<?php

declare(strict_types=1);

namespace App\Security;

use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function refreshUser(UserInterface $identity): UserInterface
    {
        if (!$identity instanceof User) {
            throw new UnsupportedUserException('Invalid user class ' . \get_class($identity));
        }

        return $identity;
    }

    public function supportsClass($class): bool
    {
        return $class === User::class;
    }

    private function loadUser($email): User
    {
        if ($user = $this->users->getByEmail($email)) {
            return $user[0];
        }

        throw new \DomainException('User is not found');
    }

    private static function identityByUser(User $user): User
    {
        return $user;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        if ($user = $this->loadUser($identifier)) {
            return self::identityByUser($user);
        }

        throw new \DomainException('User is not found');
    }
}
