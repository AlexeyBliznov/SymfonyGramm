<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\View\Subscription;

use App\Model\User\Entity\User\Account;
use Doctrine\Persistence\ManagerRegistry;

class Handler
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(Account $account, string $url = 'followers'): array
    {
        if ($url = 'subs') {
            return $this->getSubscription($account);
        } else {
            return $this->getFollowers($account);
        }
    }

    private function getSubscription(Account $account): array
    {
        $entityManager = $this->managerRegistry->getManager();
        $repository = $entityManager->getRepository(\App\Model\User\Entity\User\Follower::class);
        $followers = $repository->getByFollowerId($account);
        $result = [];

        foreach ($followers as $follower) {
            $account = $follower->getFollowedUserId();
            $result[$account->getUser()->getId()] = [
                'avatar' => $account->getAvatar(),
                'name' => $account->getFullName()
            ];
        }
        return $result;
    }

    private function getFollowers(Account $account): array
    {
        $entityManager = $this->managerRegistry->getManager();
        $repository = $entityManager->getRepository(\App\Model\User\Entity\User\Follower::class);
        $followers = $repository->getByFollowedUserId($account);
        $result = [];

        foreach ($followers as $follower) {
            $account = $follower->getFollowerId();
            $result[$account->getUser()->getId()] = [
                'avatar' => $account->getAvatar(),
                'name' => $account->getFullName()
            ];
        }
        return $result;
    }
}
