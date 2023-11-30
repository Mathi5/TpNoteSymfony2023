<?php

namespace App\Controller\Backend;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categories', name: 'admin.categories')]
class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $entityManager
    )
    {

    }
    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('backend/category/index.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $form = $this->createForm(CategoryType::class, new Category());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('success', 'La catégorie a bien été créée');

            return $this->redirectToRoute('admin.categories.index');
        }

        return $this->render('backend/category/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Category $category, Request $request): Response|RedirectResponse
    {
        if (!$category instanceof Category) {
            $this->addFlash('danger', 'La catégorie n\'existe pas');
            return $this->redirectToRoute('admin.categories.index');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('success', 'La catégorie a bien été modifié');

            return $this->redirectToRoute('admin.categories.index');
        }

        return $this->render('backend/category/update.html.twig', [
            'form' => $form,
            'category' => $category
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Category $category, Request $request) : RedirectResponse
    {
        if (!$category instanceof Category) {
            $this->addFlash('danger', 'La categorie n\'existe pas');
            return $this->redirectToRoute('admin.categories.index');
        }

        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('token'))) {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'La categorie a bien été supprimé');

            return $this->redirectToRoute('admin.categories.index');
        }

        $this->addFlash('danger', 'Le token est invalide');
        return $this->redirectToRoute('admin.categories.index');
    }

}

