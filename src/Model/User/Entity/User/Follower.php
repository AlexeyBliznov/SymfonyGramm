<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowerRepository::class)]
#[ORM\Table(name: 'followers')]
class Follower
{
    #[ORM\Id, ORM\Column(type: 'integer', unique: true)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;
    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'follower')]
    #[ORM\JoinColumn(name: 'follower_id', referencedColumnName: 'id')]
    private Account $followerId;
    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'followedUser')]
    #[ORM\JoinColumn(name: 'followed_user_id', referencedColumnName: 'id')]
    private Account $followedUserId;

    public function __construct(Account $followerId, Account $followedUserId)
    {
        $this->followerId = $followerId;
        $this->followedUserId = $followedUserId;
    }

    public function getFollowerId(): Account
    {
        return $this->followerId;
    }

    public function getFollowedUserId(): Account
    {
        return $this->followedUserId;
    }
}
