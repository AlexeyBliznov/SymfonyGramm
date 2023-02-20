<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    private \Doctrine\ORM\EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function findByConfirmToken(string $token): ?User
    {
        $repository = $this->entityManager->getRepository(User::class);
        return $repository->findOneBy(['confirmToken' =>$token]);
    }

    public function findByResetToken(string $resetToken): ?User
    {
        $repository = $this->entityManager->getRepository(User::class);
        return $repository->findOneBy(['resetToken.token' =>$resetToken]);
    }

    public function getById(int $id): User
    {
        $repository = $this->entityManager->getRepository(User::class);
        if (!$user = $repository->find($id)) {
            throw new \DomainException('User is not found.');
        }
        return $user;
    }

    public function getByEmail(string $email): User
    {
        $repository = $this->entityManager->getRepository(User::class);
        if (!$user = $repository->findOneBy(['email' => $email])) {
            throw new \DomainException('User is not found');
        }
        return $user;
    }

    public function hasByEmail(string $email): bool
    {
        $repository = $this->entityManager->getRepository(User::class);
        return $repository->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.email = :email')
                ->setParameter(':email', $email)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
