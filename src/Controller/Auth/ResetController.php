<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Model\User\Entity\User\UserRepository;
use App\Model\User\UseCase\Reset;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetController extends AbstractController
{
    #[Route("/reset", name: "auth.reset", methods: ['GET', 'POST'])]
    public function request(Request $request, Reset\Request\Handler $handler): Response
    {
        $command = new Reset\Request\Command();

        $form = $this->createForm(Reset\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Check your email.');
                return $this->redirectToRoute('home');
            } catch (\DomainException $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('security/reset/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/reset/{token}", name: "auth.reset.reset", methods: ['GET', 'POST'])]
    public function reset(string $token, Request $request, Reset\Reset\Handler $handler, UserRepository $userRepository): Response
    {
        if (!$user = $userRepository->findByResetToken($token)) {
            $this->addFlash('error', 'Incorrect or already confirmed token.');
            return $this->redirectToRoute('home');
        }

        $command = new Reset\Reset\Command($token);

        $form = $this->createForm(Reset\Reset\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Password is successfully changed.');
                return $this->redirectToRoute('home');
            } catch (\DomainException $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('security/reset/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
