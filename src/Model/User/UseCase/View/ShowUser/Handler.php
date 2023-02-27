<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\View\ShowUser;

use App\Model\User\Entity\User\Account;
use App\Model\User\ReadModel\Account\ShowUser;
use App\Model\User\ValueObject\Likes;
use App\Model\User\ValueObject\Subscription;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

class Handler
{
    private ObjectRepository $userRepository;
    private ObjectRepository $likeRepository;
    private ObjectRepository $followerRepository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $entityManager = $managerRegistry->getManager();
        $this->userRepository = $entityManager->getRepository(\App\Model\User\Entity\User\User::class);
        $this->likeRepository = $entityManager->getRepository(\App\Model\User\Entity\User\Like::class);
        $this->followerRepository = $entityManager->getRepository(\App\Model\User\Entity\User\Follower::class);
    }
    public function handle(int $id, Account $actualAccount): ShowUser
    {
        $user = $this->userRepository->getById($id);
        $account = $user->getAccount();

        $listOfLikes = $this->likeRepository->getByImageKey($account->getAvatarKey());
        $likes = new Likes($actualAccount, $listOfLikes);

        $followers = $this->followerRepository->getByFollowedUserId($account);
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
