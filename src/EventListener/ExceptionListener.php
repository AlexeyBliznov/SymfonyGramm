<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Model\User\Service\ErrorMailSender;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\RouterInterface;

class ExceptionListener
{
    private RouterInterface $router;
    private ErrorMailSender $errorMailSender;
    private string $email;

    public function __construct(RouterInterface $router, ErrorMailSender $errorMailSender,string $email)
    {
        $this->router = $router;
        $this->errorMailSender = $errorMailSender;
        $this->email = $email;
    }
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $message = $exception->getMessage();
        $code = $exception->getCode();

        if($code === Response::HTTP_NOT_FOUND) {
            $response = new RedirectResponse($this->router->generate('error404'));
        } else {
            $response = new RedirectResponse($this->router->generate('error'));
            $this->errorMailSender->send($this->email, $code, $message);
        }

        $event->setResponse($response);
    }
}
