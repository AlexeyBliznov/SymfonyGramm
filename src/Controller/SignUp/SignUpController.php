<?php

declare(strict_types=1);

namespace App\Controller\SignUp;

use App\Model\User\UseCase\SignUp;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends AbstractController
{
    #[Route('/signup', name: 'auth.signup', methods: ['GET', 'POST'])]
    public function request(Request $request, SignUp\Request\Handler $handler): Response
    {
        $command = new SignUp\Request\Command();

        $form = $this->createForm(SignUp\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Check your email');
                return $this->redirectToRoute('home');
            } catch (\DomainException $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('security/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/signup/{token}', name: 'auth.signup.confirm', methods: ['GET'])]
    public function confirm(string $token, SignUp\Confirm\Handler $handler): Response
    {
        $command = new SignUp\Confirm\Command($token);

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Email is successfully confirmed');
        } catch (\DomainException $exception) {
            $this->addFlash('error', $exception->getMessage());
            $this->redirectToRoute('home');
        }

        return $this->redirectToRoute('app_login');
    }
}
