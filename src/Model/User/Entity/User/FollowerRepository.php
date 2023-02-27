<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FollowerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Follower::class);
    }

    public function getByFollowerId(Account $followerId): array
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.followerId = :followerId')
            ->setParameter(':followerId', $followerId)
            ->getQuery()->getResult();
    }

    public function getByFollowedUserId(Account $followedUserId): array
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.followedUserId = :followedUserId')
            ->setParameter(':followedUserId', $followedUserId)
            ->getQuery()->getResult();
    }

    public function deleteSubscription(Account $followerId, Account $followedUserId): void
    {
        $this->createQueryBuilder('t')
            ->delete(Follower::class, 't')
            ->where('t.followerId = :followerId')
            ->andWhere('t.followedUserId = :followedUserId')
            ->setParameters([
                ':followerId' => $followerId,
                ':followedUserId' => $followedUserId
            ])
            ->getQuery()->execute();
    }
}
