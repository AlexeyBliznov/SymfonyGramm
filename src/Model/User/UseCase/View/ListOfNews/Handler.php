<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\View\ListOfNews;

use App\Model\User\Entity\User\Account;
use Doctrine\Persistence\ManagerRegistry;

class Handler
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(Account $account): array
    {
        $entityManager = $this->managerRegistry->getManager();
        $repository = $entityManager->getRepository(\App\Model\User\Entity\User\Follower::class);
        $followers = $repository->getByFollowerId($account);
        $result = [];

        foreach ($followers as $follower) {
            $account = $follower->getFollowedUserId();
            $news = $account->getNews()->getValues();
            foreach ($news as $value) {
                $result[$value->getDate()->format('Y-m-d H:i:s')] = [
                    'message' => $value->getMessage(),
                    'image' => $value->getImage()
                ];
            }
        }
        return $result;
    }
}
