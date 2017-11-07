<?php

namespace Corp\Http\Controllers;

use Illuminate\Http\Request;
use Corp\Repositories\PortfoliosRepository;
use Corp\Repositories\ArticlesRepository;
use Corp\Repositories\CommentsRepository;
use Corp\ArticleCategory;

use Config;

class ArticlesController extends SiteController
{

    public function __construct(PortfoliosRepository $portfolio_repa, ArticlesRepository $articles_repa, CommentsRepository $comments_repa)
    {

        parent::__construct(new \Corp\Repositories\MenusRepository(new \Corp\Menu));

        $this->portfolio_repa = $portfolio_repa;
        $this->articles_repa = $articles_repa;
        $this->comments_repa = $comments_repa;
        $this->sidebar = 'right';
        $this->template = env('THEME').'.blog';


    }
    public function index($category = false)
    {
        $this->title = "Блог";
        $this->keywords = "ключи";
        $this->meta_desc = "описание";


        $articles = $this->getArticles($category);
        $content = view(env('THEME').'.blog_content')->with('articles', $articles)->render();
        $this->data = array_add($this->data, 'content',$content);


        $portfolios =  $this->getPortfolios();
        $comments =  $this->getComments();
        $this->contentRightBar = view(env('THEME').'.blog_sidebar')->with(['portfolios'=>$portfolios, 'comments'=> $comments])->render();

        return $this->renderOutput();
    }

    public function getArticles($category = false, $alias = false, $select = false){

        $where = array();
        if($category){
            $id = ArticleCategory::select('id')->where('alias', $category)->first()->id;
            $where = ['categoryId',$id];
        }
        if($alias){
            $where = ['alias',$alias];
        }
        if(!$select) $select = ['title', 'alias', 'created_at', 'img', 'desc', 'userId', 'categoryId','id'];
        $articles = $this->articles_repa->get($select ,false, true, $where);
        if($articles){
            $articles->load('user', 'category', 'comments');
        }
        return $articles;
    }
    public function getPortfolios(){
        $portfolios = $this->portfolio_repa->get('*', Config::get('settings.blog_portfolio_count'));
        return $portfolios;
    }
    public function getComments(){
        $comments = $this->comments_repa->get('*', Config::get('settings.blog_comments_count'));
        if($comments){
            $comments->load('user', 'article');
        }
        return $comments;
    }

    public function show($alias = false)
    {

        $select = ['title', 'alias', 'created_at', 'img', 'desc', 'userId', 'categoryId','id', 'text', 'meta_desc', 'keywords'];
        $article = $this->getArticles(false, $alias, $select)[0];
        if(isset($article->id)){
            $this->title = $article->title;
            $this->keywords = $article->keywords;
            $this->meta_desc = $article->meta_desc;
        }

        $content = view(env('THEME').'.blog_article_content')->with('article', $article)->render();
        $this->data = array_add($this->data, 'content',$content);

        $portfolios =  $this->getPortfolios();
        $comments =  $this->getComments();

        $this->contentRightBar = view(env('THEME').'.blog_sidebar')->with(['portfolios'=>$portfolios, 'comments'=> $comments])->render();


        return $this->renderOutput();
    }




}
