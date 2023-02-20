<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AccountRepository extends ServiceEntityRepository
{
    private \Doctrine\ORM\EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, \Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Account::class);
        $this->entityManager = $entityManager;
    }

    public function findByUser(User $user): ?Account
    {
        $repository = $this->entityManager->getRepository(Account::class);
        return $repository->findOneBy(['user' => $user]);
    }

    public function getAllAccounts(): array
    {
        $repository = $this->entityManager->getRepository(Account::class);
        return $repository->findAll();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
