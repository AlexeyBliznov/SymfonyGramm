<?php

declare(strict_types=1);

namespace App\Model\User\ValueObject;

use App\Model\User\Entity\User\Account;

class Subscription
{
    private string $name;
    private string $url;

    public function __construct(Account $account, array $listOfFollowers)
    {
        $accounts = [];

        foreach ($listOfFollowers as $follower) {
            $accounts[] = $follower->getFollowerId();
        }

        if (!in_array($account, $accounts)) {
            $this->url = 'subscribe';
            $this->name = 'Subscribe';
        } else {
            $this->url = 'unsubscribe';
            $this->name = 'Unsubscribe';
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
