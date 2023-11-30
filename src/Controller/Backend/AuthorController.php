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
}
