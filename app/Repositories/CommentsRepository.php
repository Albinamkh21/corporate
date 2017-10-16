<?php
namespace Corp\Repositories;
use Corp\Comment;
use Corp\Repositories\Repository;

class CommentsRepository extends Repository {

    public function __construct(Comment $comment)
    {
        $this->model = $comment;
    }

}