<?php

namespace Corp\Http\Controllers;

use Illuminate\Http\Request;
use Corp\Repositories\SlidersRepository;
use Corp\Repositories\PortfoliosRepository;
use Corp\Repositories\ArticlesRepository;
use Config;


class IndexController extends SiteController
{

    public function __construct(SlidersRepository $slider_repa, PortfoliosRepository $portfolio_repa, ArticlesRepository $articles_repa)
    {

        parent::__construct(new \Corp\Repositories\MenusRepository(new \Corp\Menu));

        $this->portfolio_repa = $portfolio_repa;
        $this->sliders_repa = $slider_repa;
        $this->articles_repa = $articles_repa;
        $this->sidebar = 'right';
        $this->template = env('THEME').'.index';


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $portfoliosData = $this->getPortfolios();
        $content = view(env('THEME').'.content')->with('portfolios', $portfoliosData)->render();
        $this->data = array_add($this->data, 'content',$content);

        //обработка слайдера
        $sliderItems = $this->getSliders();
        $slider = view(env('THEME').'.slider')->with('sliders', $sliderItems)->render();
        $this->data = array_add($this->data, 'slider',$slider);

        //sidebar
        $articles = $this->getArticles();
         $this->title = "Лучшая компания";
         $this->meta_desc = " ыы ";
         $this->keywords = " ";

        $this->contentRightBar  = view(env('THEME').'.sidebar_right_home')->with('articles', $articles)->render();



        return $this->renderOutput();
    }

    public function getArticles(){
        $articles = $this->articles_repa->get(['title', 'alias', 'created_at', 'img'] , Config::get('settings.home_articles_count'));
        return $articles;
    }
    public function getPortfolios(){
        $portfolios = $this->portfolio_repa->get('*', Config::get('settings.home_portfolio_count'));
        return $portfolios;
    }
    public function getSliders(){

        $sliders = $this->sliders_repa->get();
        if($sliders->isEmpty()){
            return FALSE;
        }
        $sliders->transform(function($item, $key){
            $item->img = Config::get('settings.slider_path').'/'.$item->img;
            return $item;
        });
        return $sliders;

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
