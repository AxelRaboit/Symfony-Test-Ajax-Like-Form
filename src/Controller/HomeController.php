<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class HomeController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/', name: 'home')]
    public function index(ArticleRepository $articleRepository, ContactRepository $contactSRepository): Response
    {
        $articles = $articleRepository->findAll();
        $contacts = $contactSRepository->getAllByOrder('DESC');

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'contacts' => $contacts,
        ]);
    }
}
