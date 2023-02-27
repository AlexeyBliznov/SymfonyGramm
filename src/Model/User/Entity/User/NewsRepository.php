<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function getByAccount(Account $account): array
    {
        return $this->createQueryBuilder('t')
                ->select('t')
                ->where('t.account_id = :account')
                ->setParameter(':account', $account)
                ->getQuery()->getResult();
    }
}
