<?php

namespace App\Controller\Frontend;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/books', name: 'app.books')]
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

    #[Route('details/{id}', name: '.show', methods: ['GET'])]
    public function show(?Book $book) : Response
    {
        if (!$book instanceof Book) {
            $this->addFlash('error', 'Le livre n\'existe pas');

            return $this->redirectToRoute('app.books.index');
        }
        return $this->render('frontend/book/show.html.twig', [
            'book' => $book
        ]);
    }
}
