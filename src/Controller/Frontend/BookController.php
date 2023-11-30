<?php

namespace App\Controller\Frontend;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('', name: 'app.books')]
class BookController extends AbstractController
{
    public function __construct(
        private BookRepository $bookRepository
    )
    {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('frontend/book/index.html.twig', [
            'books' => $this->bookRepository->findAll()
        ]);
    }
}
