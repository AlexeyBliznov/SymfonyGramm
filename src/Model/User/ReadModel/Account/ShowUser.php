<?php

declare(strict_types=1);

namespace App\Model\User\ReadModel\Account;

class ShowUser
{
    private string $name;
    private string $biography;
    private string $avatar;
    private int $id;
    private int $likes;
    private ?array $news;
    private string $likeName;
    private string $likeUrl;
    private string $subName;
    private string $subUrl;

    public function __construct(
        string $name,
        string $biography,
        string $avatar,
        int $id,
        int $likes,
        string $likeName,
        string $likeUrl,
        string $subName,
        string $subUrl,
        ?array $news = null
    )
    {
        $this->name = $name;
        $this->biography = $biography;
        $this->avatar = $avatar;
        $this->id = $id;
        $this->likes = $likes;
        $this->news = $news;
        $this->likeName = $likeName;
        $this->likeUrl = $likeUrl;
        $this->subName = $subName;
        $this->subUrl = $subUrl;
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

    public function getId(): int
    {
        return $this->id;
    }

    public function getNews(): ?array
    {
        return $this->news;
    }

    public function getLikes(): int
    {
        return $this->likes;
    }

    public function getLikeName(): string
    {
        return $this->likeName;
    }

    public function getLikeUrl(): string
    {
        return $this->likeUrl;
    }

    public function getSubName(): string
    {
        return $this->subName;
    }

    public function getSubUrl(): string
    {
        return $this->subUrl;
    }
}
