<?php

namespace Corp\Http\Controllers;

use Illuminate\Http\Request;
use Corp\Repositories\PortfoliosRepository;
use Corp\Portfoliocategory;

class PortfolioController extends SiteController
{
    public function __construct(PortfoliosRepository $portfolio_repa)
    {

        parent::__construct(new \Corp\Repositories\MenusRepository(new \Corp\Menu));

        $this->portfolio_repa = $portfolio_repa;
        $this->template = env('THEME').'.portfolio';


    }
    public function index($category = false)
    {
        $this->title = "Портфолио";
        $this->keywords = "ключи";
        $this->meta_desc = "описание";


       $portfolios = $this->getPortfolios();
       $content = view(env('THEME').'.portfolio_content')->with('portfolios', $portfolios)->render();
       $this->data = array_add($this->data, 'content',$content);



        return $this->renderOutput();
    }

    public function show($alias = false)
    {
      //  $select = ['title', 'alias', 'created_at', 'img', 'desc', 'userId', 'categoryId','id', 'text', 'meta_desc', 'keywords'];
        $where = array();
        if($alias) {
            $where = ['alias', $alias ];
        }
        $portfolio = $this->portfolio_repa->getOne('*', $where);
        $otherPortfolios =  $this->getPortfolios(config('settings.portfolio_recent_projects_count'), false);
        $this->title = $portfolio->title;
        $this->keywords = $portfolio->keywords;
        $this->meta_desc = $portfolio->meta_desc;
        $content = view(env('THEME').'.portfolio_show')->with(['portfolio' => $portfolio, 'otherPortfolios' => $otherPortfolios,])->render();
        $this->data = array_add($this->data, 'content',$content);



        return $this->renderOutput();
    }
    public function getPortfolios($take = false, $pagination = true){
        $portfolios  = $this->portfolio_repa->get('*', $take, $pagination);
       /*if($portfolios){
            $portfolios->load('Portfoliocategory');
        }
       */
        return  $portfolios;
    }
}
