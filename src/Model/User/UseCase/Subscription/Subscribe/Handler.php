<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Subscription\Subscribe;

use App\Model\User\Entity\User\Account;
use App\Model\User\Entity\User\Follower;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class Handler
{
    private ObjectRepository $repository;
    private ObjectManager $entityManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->entityManager = $managerRegistry->getManager();
        $this->repository = $this->entityManager->getRepository(\App\Model\User\Entity\User\User::class);
    }

    public function handle(int $id, Account $actualAccount): void
    {
        $followedUser = $this->repository->getById($id);

        $follower = new Follower($actualAccount, $followedUser->getAccount());

        $this->entityManager->persist($follower);
        $this->entityManager->flush();
    }
}
