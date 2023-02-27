<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\View\Subscription;

use App\Model\User\Entity\User\Account;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

class Handler
{
    private ObjectRepository $repository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $entityManager = $managerRegistry->getManager();
        $this->repository = $entityManager->getRepository(\App\Model\User\Entity\User\Follower::class);
    }

    public function handle(Account $account, string $url = 'followers'): array
    {
        if ($url === 'subs') {
            return $this->getSubscription($account);
        } else {
            return $this->getFollowers($account);
        }
    }

    private function getSubscription(Account $account): array
    {
        $followers = $this->repository->getByFollowerId($account);
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
        $followers = $this->repository->getByFollowedUserId($account);
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
