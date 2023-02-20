<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
#[ORM\Table(name: 'news')]
class News
{
    #[ORM\Id, ORM\Column(type: 'integer', unique: true)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;
    #[ORM\Column(name: 'date', type: 'datetime_immutable')]
    private \DateTimeImmutable $date;
    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'news')]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id')]
    private Account $account;
    #[ORM\Column(name: 'message', type: 'string', nullable: true)]
    private string|null $message;
    #[ORM\Column(name: 'image', type: 'string', nullable: true)]
    private string|null $image;
    #[ORM\Column(name: 'image_key', type: 'string', nullable: true)]
    private string|null $imageKey;

    public function __construct(Account $account, \DateTimeImmutable $date, string $message = null, string $image = null, string $imageKey = null)
    {
        $this->account = $account;
        $this->date = $date;
        $this->message = $message;
        $this->image = $image;
        $this->imageKey = $imageKey;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getImageKey(): string
    {
        return $this->imageKey;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }
}
