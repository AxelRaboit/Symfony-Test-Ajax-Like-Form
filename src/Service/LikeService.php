<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use App\Entity\User;
use App\Entity\Like;
use App\Repository\LikeRepository;

class LikeService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LikeRepository         $likeRepository
    ) {
    }

    public function persistLike(
        Article $article,
        User $user
    ): array
    {
        $result = [];

        $like = $this->likeRepository->findOneBy(['article' => $article, 'person' => $user]);

        if ($like) {
            $this->removeAndFlush($like);
            $result['status'] = Like::LIKE_REMOVED;
        } else {
            $like = new Like();
            $like->setArticle($article);
            $like->setPerson($user);
            $this->persistAndFlush($like);
            $result['status'] = Like::LIKE_ADDED;
        }

        $result['likeCount'] = $this->likeRepository->count(['article' => $article]);

        return $result;
    }

    // base

    public function persistAndFlush(object $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function removeAndFlush(object $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}