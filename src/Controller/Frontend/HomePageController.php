<?php

namespace App\Controller\Frontend;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    public function __construct(
        private BookRepository $bookRepository
    )
    { }
    #[Route('', name: 'Frontend.homepage')]
    public function index(): Response
    {
        return $this->render('frontend/home_page/index.html.twig', [
            'books' => $this->bookRepository->findLatestWithLimit(3)
        ]);
    }
}
