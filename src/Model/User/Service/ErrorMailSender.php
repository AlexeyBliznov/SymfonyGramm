<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ErrorMailSender extends AbstractController
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function send(string $adminEmail, int|null $code, string $message): void
    {
        $mail = (new Mime\Email())
            ->to($adminEmail)
            ->subject('New error')
            ->text('New bug found')
            ->html($this->twig->render('mail/error.html.twig', [
                'code' => $code,
                'message' => $message
            ]));

        $this->mailer->send($mail);
    }
}
