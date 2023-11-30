<?php

namespace App\Controller\Backend;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/authors', name: 'admin.authors')]
class AuthorController extends AbstractController
{
    public function __construct(
        private AuthorRepository $authorRepository,
        private EntityManagerInterface $entityManager
    )
    {

    }
    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('backend/author/index.html.twig', [
            'authors' => $this->authorRepository->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $form = $this->createForm(AuthorType::class, new Author());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->getData();
            $this->entityManager->persist($author);
            $this->entityManager->flush();

            $this->addFlash('success', 'L\'auteur a bien été créée');

            return $this->redirectToRoute('admin.authors.index');
        }

        return $this->render('backend/author/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Author $author, Request $request): Response|RedirectResponse
    {
        if (!$author instanceof Author) {
            $this->addFlash('danger', 'L\'Auteur n\'existe pas');
            return $this->redirectToRoute('admin.authors.index');
        }

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($author);
            $this->entityManager->flush();

            $this->addFlash('success', 'L\'Auteur a bien été modifié');

            return $this->redirectToRoute('admin.authors.index');
        }

        return $this->render('backend/author/update.html.twig', [
            'form' => $form,
            'author' => $author
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Author $author, Request $request) : RedirectResponse
    {
        if (!$author instanceof Author) {
            $this->addFlash('danger', 'L\'Auteur n\'existe pas');
            return $this->redirectToRoute('admin.authors.index');
        }

        if ($this->isCsrfTokenValid('delete' . $author->getId(), $request->request->get('token'))) {
            $this->entityManager->remove($author);
            $this->entityManager->flush();
            $this->addFlash('success', 'L\'Auteur a bien été supprimé');

            return $this->redirectToRoute('admin.authors.index');
        }

        $this->addFlash('danger', 'Le token est invalide');
        return $this->redirectToRoute('admin.authors.index');
    }
}
