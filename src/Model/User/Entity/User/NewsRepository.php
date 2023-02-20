<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NewsRepository extends ServiceEntityRepository
{
    private \Doctrine\ORM\EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, \Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, News::class);
        $this->entityManager = $entityManager;
    }

    public function getByAccount(Account $account): array
    {
        $repository = $this->entityManager->getRepository(News::class);
        return $repository->createQueryBuilder('t')
                ->select('t')
                ->where('t.account_id = :account')
                ->setParameter(':account', $account)
                ->getQuery()->getResult();
    }
}
