<?php

namespace App\Controller\Backend;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/books', name: 'admin.books')]
class BookController extends AbstractController
{
    public function __construct(
        private BookRepository $bookRepository,
        private EntityManagerInterface $entityManager

    )
    {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('backend/book/index.html.twig', [
            'books' => $this->bookRepository->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request) : Response|RedirectResponse
    {
        $form = $this->createForm(BookType::class, new Book());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $this->entityManager->persist($book);
            $this->entityManager->flush();

            $this->addFlash('success', 'L\'article a bien été créé');

            return $this->redirectToRoute('admin.books.index');
        }

        return $this->render('backend/book/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Book $book, Request $request) : Response|RedirectResponse
    {
        if (!$book instanceof Book) {
            $this->addFlash('danger', 'L\'article n\'existe pas');
            return $this->redirectToRoute('admin.books.index');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($book);
            $this->entityManager->flush();

            $this->addFlash('success', 'L\'article a bien été modifié');

            return $this->redirectToRoute('admin.books.index');
        }

        return $this->render('backend/book/update.html.twig', [
            'form' => $form,
            'book' => $book
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Book $book, Request $request) : RedirectResponse
    {
        if (!$book instanceof Book) {
            $this->addFlash('danger', 'L\'article n\'existe pas');
            return $this->redirectToRoute('admin.books.index');
        }

        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->request->get('token'))) {
            $this->entityManager->remove($book);
            $this->entityManager->flush();
            $this->addFlash('success', 'L\'article a bien été supprimé');

            return $this->redirectToRoute('admin.books.index');
        }

        $this->addFlash('danger', 'Le token est invalide');
        return $this->redirectToRoute('admin.books.index');
    }
}
