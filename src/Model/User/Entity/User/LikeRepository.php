<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function getByAccount(Account $account): array
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.account = :account')
            ->setParameter(':account', $account)
            ->getQuery()->getResult();
    }

    public function getByImageKey(string $imageKey): array
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.imageKey = :imageKey')
            ->setParameter(':imageKey', $imageKey)
            ->getQuery()->getResult();
    }

    public function deleteLike(Account $account, string $imageKey)
    {
        $this->createQueryBuilder('t')
            ->delete(Like::class, 't')
            ->where('t.account = :account')
            ->andWhere('t.imageKey = :imageKey')
            ->setParameters([
                ':account' => $account,
                ':imageKey' => $imageKey
            ])
            ->getQuery()->execute();
    }
}
