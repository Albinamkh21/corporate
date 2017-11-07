<?php

namespace Corp\Http\Controllers\Admin;

use Corp\Http\Requests\MenuRequest;
use Corp\Repositories\ArticlesRepository;
use Corp\Repositories\MenusRepository;
use Corp\Repositories\PortfoliosRepository;
use Illuminate\Http\Request;
use Corp\Http\Controllers\Controller;
use Menu;
use Gate;
use Log;
use Corp\ArticleCategory;

class MenuController extends AdminController
{
    protected $menu_repa;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(MenusRepository $menu_repa, ArticlesRepository $articles_repa, PortfoliosRepository $portfolios_repa)
    {
        parent::__construct();
        $this->articles_repa = $articles_repa;
        $this->portfolio_repa = $portfolios_repa;
        $this->menu_repa = $menu_repa;
        $this->template = env('THEME').'.admin.menus';
    }

    public function index()
    {
        $this->checkUser();
        if(Gate::denies('VIEW_ADMIN_MENU', $this->user)){
            abort(403, "У Вас недостаточно прав!");
        }

        $this->title = 'Управление меню';
        $menu = $this->getMenus();

        $this->content = view(config('settings.theme').'.admin.menus_content')->with('menus',$menu)->render();
        return $this->renderOutput();
    }
    public function getMenus()
    {
        //

        $menu = $this->menu_repa->get();

        if($menu->isEmpty()) {
            return FALSE;
        }

        return Menu::make('forMenuPart', function($m) use($menu) {

            foreach($menu as $item) {
                if($item->parentId == 0) {
                    $m->add($item->title,$item->path)->id($item->id);
                }

                else {
                    if($m->find($item->parentId)) {
                        $m->find($item->parentId)->add($item->title,$item->path)->id($item->id);
                    }
                }
            }

        });


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->title = 'Новый пункт меню';

        $tmp = $this->getMenus()->roots();

        //null
        $menus = $tmp->reduce(function($returnMenus, $menu) {

            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;

        },['0' => 'Родительский пункт меню']);

        $categories = ArticleCategory::select(['title','alias','parentId','id'])->get();

        $list = array();
        $list = array_add($list,'0','Не используется');
        $list = array_add($list,'parent','Раздел блог');

        foreach($categories as $category) {
            if($category->parentId == 0) {
                $list[$category->title] = array();
            }
            else {
                $list[$categories->where('id',$category->parentId)->first()->title][$category->alias] = $category->title;
            }
        }

        $articles = $this->articles_repa->get(['id','title','alias']);

        $articles = $articles->reduce(function ($returnArticles, $article) {
            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;
        }, []);


        $filters = \Corp\Portfoliocategory::select('id','title','alias')->get()->reduce(function ($returnFilters, $filter) {
            $returnFilters[$filter->alias] = $filter->title;
            return $returnFilters;
        }, ['parent' => 'Раздел портфолио']);

        $portfolios = $this->portfolio_repa->get(['id','alias','title'])->reduce(function ($returnPortfolios, $portfolio) {
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        }, []);

        $this->content = view(config('settings.theme').'.admin.menus_create_content')->with(['menus'=>$menus,'categories'=>$list,'articles'=>$articles,'filters' => $filters,'portfolios' => $portfolios])->render();



        return $this->renderOutput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $request)
    {

        $result = $this->menu_repa->addMenu($request);

        if(is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
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
    public function edit(\Corp\Menu $menu)
    {
       //
        $this->title = 'Редактирование ссылки - '.$menu->title;

        $type = FALSE;
        $option = FALSE;

        //path - http://corporate.loc/articles

        $route = app('router')->getRoutes()->match(app('request')->create($menu->path));
        $aliasRoute = $route->getName();
        $parameters = $route->parameters();

         dump($aliasRoute);
         dump($parameters);

        if($aliasRoute == 'articles.index' || $aliasRoute == 'articles_category') {
            $type = 'blogLink';
            $option = isset($parameters['category']) ? $parameters['category'] : 'parent';
        }

        else if($aliasRoute == 'articles.show') {
            $type = 'blogLink';
            $option = isset($parameters['alias']) ? $parameters['alias'] : '';

        }

        else if($aliasRoute == 'portfolio.index') {
            $type = 'portfolioLink';
            $option = 'parent';

        }

        else if($aliasRoute == 'portfolio.show') {
            $type = 'portfolioLink';
            $option = isset($parameters['alias']) ? $parameters['alias'] : '';

        }

        else {
            $type = 'customLink';
        }



        //dd($type);
        $tmp = $this->getMenus()->roots();

        //null
        $menus = $tmp->reduce(function($returnMenus, $menu) {

            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;

        },['0' => 'Родительский пункт меню']);

        $categories = \Corp\ArticleCategory::select(['title','alias','parentId','id'])->get();

        $list = array();
        $list = array_add($list,'0','Не используется');
        $list = array_add($list,'parent','Раздел блог');

        foreach($categories as $category) {
            if($category->parentId == 0) {
                $list[$category->title] = array();
            }
            else {
                $list[$categories->where('id',$category->parentId)->first()->title][$category->alias] = $category->title;
            }
        }

        $articles = $this->articles_repa->get(['id','title','alias']);

        $articles = $articles->reduce(function ($returnArticles, $article) {
            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;
        }, []);


        $filters = \Corp\Portfoliocategory::select('id','title','alias')->get()->reduce(function ($returnFilters, $filter) {
            $returnFilters[$filter->alias] = $filter->title;
            return $returnFilters;
        }, ['parent' => 'Раздел портфолио']);

        $portfolios = $this->portfolio_repa->get(['id','alias','title'])->reduce(function ($returnPortfolios, $portfolio) {
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        }, []);

        $this->content = view(config('settings.theme').'.admin.menus_create_content')->with(['menu' => $menu,'type' => $type,'option' => $option,'menus'=>$menus,'categories'=>$list,'articles'=>$articles,'filters' => $filters,'portfolios' => $portfolios])->render();



        return $this->renderOutput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, \Corp\Menu $menu)
    {
        //
        $result = $this->menu_repa->updateMenu($request,$menu);

        if(is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(\Corp\Menu $menu)
    {
        $result = $this->menu_repa->deleteMenu($menu);

        if(is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }
}
