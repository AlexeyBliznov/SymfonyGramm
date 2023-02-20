<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\News\AddNews;

use App\Model\User\Entity\User\News;
use Doctrine\Persistence\ManagerRegistry;

class Handler
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(Command $command, $image): void
    {
        $entityManager = $this->managerRegistry->getManager();

        $news = new News($command->account, new \DateTimeImmutable(), $command->message, $image, $command->imageKey);

        $entityManager->persist($news);
        $entityManager->flush();
    }
}
