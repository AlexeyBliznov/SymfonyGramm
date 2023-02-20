<?php

namespace App\DataFixtures;

use App\Model\User\Entity\User\Account;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\Email;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private PasswordHasher $passwordHasher;

    public function __construct(PasswordHasher $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            'File1.jpg' => [
                'img' => 'https://symfonygramm.s3.amazonaws.com/File1.jpg',
                'email' => 'test1@gmail.com'
            ],
            'File2.jpg' => [
                'img' => 'https://symfonygramm.s3.amazonaws.com/File2.jpg',
                'email' => 'developer@gmail.com'
            ],
            'File4.jpg' => [
                'img' => 'https://symfonygramm.s3.amazonaws.com/File4.jpg',
                'email' => 'user@gmail.com'
            ]
        ];

        $names = [
            'Jonh',
            'Alex',
            'Michael'
        ];
        $lastNames = [
            'Smith',
            'Johnson',
            'Jones'
        ];

        foreach ($data as $key => $value) {
            $user = new User();
            $user->create(new Email($value['email']), $this->passwordHasher->hash('password'), 'token');
            $user->confirmSignUp();
            $manager->persist($user);
            $account = new Account($user, $names[mt_rand(0,2)], $lastNames[mt_rand(0,2)], 'No biography', $value['img'], $key);
            $manager->persist($account);
        }

        $manager->flush();
    }
}
