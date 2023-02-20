<?php

declare(strict_types=1);

namespace App\Model\User\ValueObject;

use App\Model\User\Entity\User\Account;

class Likes
{
    private string $name;
    private string $url;

    public function __construct(Account $account, array $listOfLikes)
    {
        $accounts = [];

        foreach ($listOfLikes as $value) {
            $accounts[] = $value->getAccount();
        }

        if (!in_array($account, $accounts)) {
            $this->url = 'like';
            $this->name = 'Like';
        } else {
            $this->url = 'dislike';
            $this->name = 'Dislike';
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
