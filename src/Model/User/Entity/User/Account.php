<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: 'accounts')]
class Account
{
    #[ORM\Id, ORM\Column(type: 'integer', unique: true)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;
    #[ORM\OneToOne(inversedBy: 'account', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private UserInterface $user;
    #[ORM\Column(name: 'first_name', type: 'string')]
    private string $firstName;
    #[ORM\Column(name: 'last_name', type: 'string')]
    private string $lastName;
    #[ORM\Column(name: 'biography', type: 'string')]
    private string $biography;
    #[ORM\Column(name: 'avatar', type: 'string')]
    private string $avatar;
    #[ORM\Column(name: 'avatar_key', type: 'string')]
    private string $avatarKey;
    #[ORM\OneToMany(mappedBy: 'account', targetEntity: News::class)]
    private Collection $news;
    #[ORM\OneToMany(mappedBy: 'followerId', targetEntity: Follower::class)]
    private Collection $follower;
    #[ORM\OneToMany(mappedBy: 'followedUserId', targetEntity: Follower::class)]
    private Collection $followedUser;
    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Like::class)]
    private Collection $like;

    public function __construct(UserInterface $user, string $firstName, string $lastName, string $biography, string $avatar, string $avatarKey)
    {
        $this->user = $user;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->biography = $biography;
        $this->avatar = $avatar;
        $this->avatarKey = $avatarKey;
        $this->news = new ArrayCollection();
        $this->follower = new ArrayCollection();
        $this->followedUser = new ArrayCollection();
        $this->like = new ArrayCollection();
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getBiography(): string
    {
        return $this->biography;
    }

    public function setBiography(string $biography): void
    {
        $this->biography = $biography;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getAvatarKey(): string
    {
        return $this->avatarKey;
    }

    public function setAvatarKey(string $avatarKey): void
    {
        $this->avatarKey = $avatarKey;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getNews(): ArrayCollection|Collection
    {
        return $this->news;
    }

    public function getFollower(): ArrayCollection|Collection
    {
        return $this->follower;
    }

    public function getFollowedUser(): ArrayCollection|Collection
    {
        return $this->followedUser;
    }
}
