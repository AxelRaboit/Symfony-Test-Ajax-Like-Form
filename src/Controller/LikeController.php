<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Service\LikeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'article_like')]
    public function like(
        Article $article,
        LikeService $likeService
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User not logged in'], 403);
        }

        $response = $likeService->persistLike($article, $user);

        return $this->json(['status' => $response['status'], 'likes' => $response['likeCount']]);
    }
}
