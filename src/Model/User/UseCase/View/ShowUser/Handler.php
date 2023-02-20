<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\View\ShowUser;

use App\Model\User\Entity\User\Account;
use App\Model\User\ReadModel\Account\ShowUser;
use App\Model\User\ValueObject\Likes;
use App\Model\User\ValueObject\Subscription;
use Doctrine\Persistence\ManagerRegistry;

class Handler
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(int $id, Account $actualAccount): ShowUser
    {
        $entityManager = $this->managerRegistry->getManager();

        $userRepository = $entityManager->getRepository(\App\Model\User\Entity\User\User::class);
        $user = $userRepository->getById($id);
        $account = $user->getAccount();

        $likeRepository = $entityManager->getRepository(\App\Model\User\Entity\User\Like::class);
        $listOfLikes = $likeRepository->getByImageKey($account->getAvatarKey());
        $likes = new Likes($actualAccount, $listOfLikes);

        $followerRepository = $entityManager->getRepository(\App\Model\User\Entity\User\Follower::class);
        $followers = $followerRepository->getByFollowedUserId($account);
        $subscription = new Subscription($actualAccount, $followers);

        $news = $account->getNews()->getValues();
        $newsResult = [];

        foreach ($news as $value) {
            $newsResult[$value->getMessage()] = [
                'date' => $value->getDate()->format('Y-m-d H:i:s'),
                'image' => $value->getImage()
            ];
        }

        return new ShowUser(
            $account->getFullName(),
            $account->getBiography(),
            $account->getAvatar(),
            $id,
            count($listOfLikes),
            $likes->getName(),
            $likes->getUrl(),
            $subscription->getName(),
            $subscription->getUrl(),
            $newsResult
        );
    }
}
