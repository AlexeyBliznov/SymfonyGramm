<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Subscription\Subscribe;

use App\Model\User\Entity\User\Account;
use App\Model\User\Entity\User\Follower;
use Doctrine\Persistence\ManagerRegistry;

class Handler
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(int $id, Account $actualAccount): void
    {
        $entityManager = $this->managerRegistry->getManager();

        $repository = $entityManager->getRepository(\App\Model\User\Entity\User\User::class);
        $followedUser = $repository->getById($id);

        $follower = new Follower($actualAccount, $followedUser->getAccount());

        $entityManager->persist($follower);
        $entityManager->flush();
    }
}
