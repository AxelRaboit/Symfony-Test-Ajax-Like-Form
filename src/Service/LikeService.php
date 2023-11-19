<?php

namespace App\Service;

use App\Manager\LikeManager;
use App\Entity\Article;
use App\Entity\User;

class LikeService
{
    public function __construct(
        private readonly LikeManager $likeManager
    ) {
    }

    public function addOrRemoveLike(
        Article $article,
        User $user
    ): array
    {
         return $this->likeManager->persistLike($article, $user);
    }
}