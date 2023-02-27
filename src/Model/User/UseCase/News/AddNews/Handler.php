<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\News\AddNews;

use App\Model\User\Entity\User\News;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class Handler
{
    private ObjectManager $entityManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->entityManager = $managerRegistry->getManager();
    }

    public function handle(Command $command, $image): void
    {
        $news = new News($command->account, new \DateTimeImmutable(), $command->message, $image, $command->imageKey);

        $this->entityManager->persist($news);
        $this->entityManager->flush();
    }
}
