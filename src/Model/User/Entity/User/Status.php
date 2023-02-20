<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Webmozart\Assert\Assert;

class Status
{
    private const STATUS_NEW = 'new';
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    private string $status;

    public function __construct(string $status)
    {
        Assert::oneOf($status, [
            self::STATUS_NEW,
            self::STATUS_WAIT,
            self::STATUS_ACTIVE
        ]);

        $this->status = $status;
    }

    public static function new(): self
    {
        return new self(self::STATUS_NEW);
    }

    public static function wait(): self
    {
        return new self(self::STATUS_WAIT);
    }

    public static function active(): self
    {
        return new self(self::STATUS_ACTIVE);
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getValue(): string
    {
        return $this->status;
    }
}
