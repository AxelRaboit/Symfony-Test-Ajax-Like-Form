<?php

namespace App\Manager;

use App\Entity\Article;
use App\Entity\Like;
use App\Entity\User;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;

class LikeManager
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

        if ($like && $like->getPerson() !== $user) {
            $result['status'] = 'error';
            return $result;
        }

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

    private function persistAndFlush(Like $like): void
    {
        $this->entityManager->persist($like);
        $this->entityManager->flush();
    }

    private function removeAndFlush(Like $like): void
    {
        $this->entityManager->remove($like);
        $this->entityManager->flush();
    }
}