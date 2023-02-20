<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

class LikeRepository extends ServiceEntityRepository
{
    private \Doctrine\ORM\EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, \Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Like::class);
        $this->entityManager = $entityManager;
    }

    public function getByAccount(Account $account): array
    {
        $repository = $this->entityManager->getRepository(Like::class);
        return $repository->createQueryBuilder('t')
            ->select('t')
            ->where('t.account = :account')
            ->setParameter(':account', $account)
            ->getQuery()->getResult();
    }

    public function getByImageKey(string $imageKey): array
    {
        $repository = $this->entityManager->getRepository(Like::class);
        return $repository->createQueryBuilder('t')
            ->select('t')
            ->where('t.imageKey = :imageKey')
            ->setParameter(':imageKey', $imageKey)
            ->getQuery()->getResult();
    }

    public function deleteLike(Account $account, string $imageKey)
    {
        $repository = $this->entityManager->getRepository(Like::class);
        $repository->createQueryBuilder('t')
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
