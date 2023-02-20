<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FollowerRepository extends ServiceEntityRepository
{
    private \Doctrine\ORM\EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, \Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Follower::class);
        $this->entityManager = $entityManager;
    }

    public function getByFollowerId(Account $followerId): array
    {
        $repository = $this->entityManager->getRepository(Follower::class);
        return $repository->createQueryBuilder('t')
            ->select('t')
            ->where('t.followerId = :followerId')
            ->setParameter(':followerId', $followerId)
            ->getQuery()->getResult();
    }

    public function getByFollowedUserId(Account $followedUserId): array
    {
        $repository = $this->entityManager->getRepository(Follower::class);
        return $repository->createQueryBuilder('t')
            ->select('t')
            ->where('t.followedUserId = :followedUserId')
            ->setParameter(':followedUserId', $followedUserId)
            ->getQuery()->getResult();
    }

    public function deleteSubscription(Account $followerId, Account $followedUserId): void
    {
        $repository = $this->entityManager->getRepository(Follower::class);
        $repository->createQueryBuilder('t')
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
