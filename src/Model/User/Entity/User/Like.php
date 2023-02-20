<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: 'likes')]
class Like
{
    #[ORM\Id, ORM\Column(type: 'integer', unique: true)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;
    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'like')]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id')]
    private Account $account;
    #[ORM\Column(type: 'string')]
    private string $imageKey;

    public function __construct(Account $account, string $imageKey)
    {
        $this->account = $account;
        $this->imageKey = $imageKey;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getImageKey(): string
    {
        return $this->imageKey;
    }
}
