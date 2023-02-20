<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'error', methods: 'GET')]
    public function error(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error.html.twig');
    }

    #[Route('/error404', name: 'error404', methods: 'GET')]
    public function error404(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
    }
}
