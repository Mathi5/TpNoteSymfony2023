<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/frontend/book', name: 'app_frontend_book')]
    public function index(): Response
    {
        return $this->render('frontend/book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
}
