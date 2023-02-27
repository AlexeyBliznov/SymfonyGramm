<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Subscription\DeleteSubscription;

use App\Model\User\Entity\User\Account;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

class Handler
{
    private ObjectRepository $userRepository;
    private ObjectRepository $followerRepository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $entityManager = $managerRegistry->getManager();
        $this->userRepository = $entityManager->getRepository(\App\Model\User\Entity\User\User::class);
        $this->followerRepository = $entityManager->getRepository(\App\Model\User\Entity\User\Follower::class);

    }

    public function handle(int $id, Account $actualAccount): void
    {
        $user = $this->userRepository->getById($id);

        $followedUserAccount = $user->getAccount();

        $this->followerRepository->deleteSubscription($actualAccount, $followedUserAccount);
    }
}
