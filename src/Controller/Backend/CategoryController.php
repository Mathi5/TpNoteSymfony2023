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
        private CategoryRepository $categorieRepository,
        private EntityManagerInterface $entityManager
    )
    {

    }
    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('backend/category/index.html.twig', [
            'categories' => $this->categorieRepository->findAll(),
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
}


//
//
//    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
//    public function update(?Categorie $categorie, Request $request): Response|RedirectResponse
//    {
//        if (!$categorie instanceof Categorie) {
//            $this->addFlash('danger', 'La catégorie n\'existe pas');
//            return $this->redirectToRoute('admin.categories.index');
//        }
//
//        $form = $this->createForm(CategorieType::class, $categorie);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->entityManager->persist($categorie);
//            $this->entityManager->flush();
//
//            $this->addFlash('success', 'La catégorie a bien été modifié');
//
//            return $this->redirectToRoute('admin.categories.index');
//        }
//
//        return $this->render('backend/categorie/update.html.twig', [
//            'form' => $form,
//            'categorie' => $categorie
//        ]);
//    }
//
//    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
//    public function delete(?Categorie $categorie, Request $request) : RedirectResponse
//    {
//        if (!$categorie instanceof Categorie) {
//            $this->addFlash('danger', 'L\'categorie n\'existe pas');
//            return $this->redirectToRoute('admin.categories.index');
//        }
//
//        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('token'))) {
//            $this->entityManager->remove($categorie);
//            $this->entityManager->flush();
//            $this->addFlash('success', 'L\'categorie a bien été supprimé');
//
//            return $this->redirectToRoute('admin.categories.index');
//        }
//
//        $this->addFlash('danger', 'Le token est invalide');
//        return $this->redirectToRoute('admin.categories.index');
//    }
//
//    #[Route('/{id}/visibility', name: '.switch', methods: ['GET'])]
//    public function switch(?Categorie $categorie): JsonResponse
//    {
//        if (!$categorie instanceof Categorie) {
//            return new JsonResponse([
//                'status' => 'Error',
//                'message' => 'La catégorie n\'existe pas'
//            ], Response::HTTP_NOT_FOUND);
//        }
//
//        $categorie->setEnable(!$categorie->isEnable());
//        $this->entityManager->persist($categorie);
//        $this->entityManager->flush();
//
//        return $this->json([
//            'status' => 'success',
//            'message' => 'La catégorie a bien été modifié',
//            'visibility' => $categorie->isEnable()
//        ]);
//    }
//}