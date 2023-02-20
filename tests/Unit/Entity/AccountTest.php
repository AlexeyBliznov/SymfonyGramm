<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Model\User\Entity\User\Account;
use App\Tests\Unit\UserBuilder;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function testCreate(): void
    {
        $builder = new UserBuilder();
        $user = $builder->buildSignedUpUser();
        $account = new Account($user);

        $account->create('First', 'Last', 'Bio', 'img', '123');

        $this->assertEquals('First Last', $account->getFirstName() . ' ' . $account->getLastName());
        $this->assertEquals('Bio', $account->getBiography());
        $this->assertEquals('img', $account->getAvatar());
        $this->assertEquals('123', $account->getAvatarKey());
    }

    public function testEdit(): void
    {
        $builder = new UserBuilder();
        $user = $builder->buildSignedUpUser();
        $account = new Account($user);

        $account->create('First', 'Last', 'Bio', 'img', '123');

        $account->setFirstName('First1');
        $account->setAvatar('JPEG');

        $this->assertEquals('First1 Last', $account->getFirstName() . ' ' . $account->getLastName());
        $this->assertEquals('Bio', $account->getBiography());
        $this->assertEquals('JPEG', $account->getAvatar());
        $this->assertEquals('123', $account->getAvatarKey());
    }
}