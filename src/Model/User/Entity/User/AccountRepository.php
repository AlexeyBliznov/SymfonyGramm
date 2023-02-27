<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function findByUser(User $user): ?Account
    {
        return $this->findOneBy(['user' => $user]);
    }

    public function getAllAccounts(): array
    {
        return $this->findAll();
    }

    public function flush(): void
    {
        $this->flush();
    }
}
