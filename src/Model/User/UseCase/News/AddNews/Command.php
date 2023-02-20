<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\News\AddNews;

use App\Model\User\Entity\User\Account;

class Command
{
    public Account $account;
    public string $message;
    public string $image;
    public string $imageKey;
}
