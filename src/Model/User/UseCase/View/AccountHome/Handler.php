<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\View\AccountHome;

use App\Model\User\Entity\User\Account;
use App\Model\User\ReadModel\Account\AccountHome;
use Doctrine\Persistence\ManagerRegistry;

class Handler
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(Account $account): AccountHome
    {
        $entityManager = $this->managerRegistry->getManager();

        $name = $account->getFirstName() . ' ' . $account->getLastName();
        $biography = $account->getBiography();
        $avatar = $account->getAvatar();
        $news = $account->getNews()->getValues();
        $newsResult = [];

        foreach ($news as $value) {
            $newsResult[$value->getMessage()] = [
                'date' => $value->getDate()->format('Y-m-d H:i:s'),
                'image' => $value->getImage()
            ];
        }

        $likeRepository = $entityManager->getRepository(\App\Model\User\Entity\User\Like::class);
        $likes = $likeRepository->getByImageKey($account->getAvatarKey());


        return new AccountHome($name, $biography, $avatar, count($likes), $newsResult);
    }
}
