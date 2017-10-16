<?php
namespace Corp\Repositories;
use Corp\Article;
use Corp\Repositories\Repository;

class ArticlesRepository extends Repository {

    public function __construct(Article $article)
    {
        $this->model = $article;
    }

}