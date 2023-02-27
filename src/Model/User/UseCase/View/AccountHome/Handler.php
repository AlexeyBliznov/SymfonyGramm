<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\View\AccountHome;

use App\Model\User\Entity\User\Account;
use App\Model\User\ReadModel\Account\AccountHome;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

class Handler
{
    private ObjectRepository $repository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $entityManager = $managerRegistry->getManager();
        $this->repository = $entityManager->getRepository(\App\Model\User\Entity\User\Like::class);
    }

    public function handle(Account $account): AccountHome
    {
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

        $likes = $this->repository->getByImageKey($account->getAvatarKey());


        return new AccountHome($name, $biography, $avatar, count($likes), $newsResult);
    }
}
