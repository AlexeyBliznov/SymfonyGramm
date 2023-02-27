<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByConfirmToken(string $token): ?User
    {
        return $this->findOneBy(['confirmToken' =>$token]);
    }

    public function findByResetToken(string $resetToken): ?User
    {
        return $this->findOneBy(['resetToken.token' =>$resetToken]);
    }

    public function getById(int $id): User
    {
        if (!$user = $this->find($id)) {
            throw new \DomainException('User is not found.');
        }
        return $user;
    }

    public function getByEmail(string $email): User
    {
        if (!$user = $this->findOneBy(['email' => $email])) {
            throw new \DomainException('User is not found');
        }
        return $user;
    }

    public function hasByEmail(string $email): bool
    {
        return $this->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.email = :email')
                ->setParameter(':email', $email)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function flush(): void
    {
        $this->flush();
    }
}
