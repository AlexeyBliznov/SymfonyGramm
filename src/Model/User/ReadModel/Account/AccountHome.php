<?php

declare(strict_types=1);

namespace App\Model\User\ReadModel\Account;

class AccountHome
{
    private string $name;
    private string $biography;
    private string $avatar;
    private int $likes;
    private ?array $news;

    public function __construct(string $name, string $biography, string $avatar, int $likes, ?array $news = null)
    {
        $this->name = $name;
        $this->biography = $biography;
        $this->avatar = $avatar;
        $this->likes = $likes;
        $this->news = $news;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBiography(): string
    {
        return $this->biography;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function getNews(): ?array
    {
        return $this->news;
    }

    public function getLikes(): int
    {
        return $this->likes;
    }
}
