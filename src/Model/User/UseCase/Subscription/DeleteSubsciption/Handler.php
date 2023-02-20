<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Subscription\DeleteSubscription;

use App\Model\User\Entity\User\Account;
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
        $user = $repository->getById($id);

        $followedUserAccount = $user->getAccount();

        $followerRepository = $entityManager->getRepository(\App\Model\User\Entity\User\Follower::class);
        $followerRepository->deleteSubscription($actualAccount, $followedUserAccount);
    }
}
