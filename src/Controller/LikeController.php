<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Like;
use App\Entity\User;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'article_like')]
    public function like(
        Article $article,
        EntityManagerInterface $em,
        LikeRepository $likeRepository
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User not logged in'], 403);
        }

        $like = $likeRepository->findOneBy(['article' => $article, 'person' => $user]);

        if ($like) {
            $em->remove($like);
            $em->flush();
            $status = Like::LIKE_REMOVED;
        } else {
            $like = new Like();
            $like->setArticle($article);
            $like->setPerson($user);
            $em->persist($like);
            $em->flush();
            $status = Like::LIKE_ADDED;
        }

        $likeCount = $likeRepository->count(['article' => $article]);

        return $this->json(['status' => $status, 'likes' => $likeCount]);
    }
}
