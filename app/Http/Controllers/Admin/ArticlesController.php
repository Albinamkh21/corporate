<?php

namespace Corp\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Corp\Http\Controllers\Controller;
use Corp\Repositories\ArticlesRepository;
use Gate;

use Corp\ArticleCategory as Category;
use Corp\Http\Requests\ArticleRequest;
use Corp\Article;

class ArticlesController extends AdminController
{
    public function __construct(ArticlesRepository $articlesRepository)
    {
        parent ::__construct();
        $this->articles_repa = $articlesRepository;
        $this->template = env('THEME').'.admin.articles';
    }

    public function index() {
        $this->checkUser();
        if(Gate::denies('VIEW_ADMIN_ARTICLES', $this->user)){
            abort(403, "У Вас недостаточно прав!");
        }

        $this->title = 'Управление статьями';
        $articles = $this->getArticles();
        $content = view(env('THEME').'.admin.articles_content')->with('articles',$articles)->render();
        $this->data = array_add($this->data, 'content',$content);

        return $this->renderOutput();

    }
    public function getArticles(){
        return $this->articles_repa->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('save', new Article)){
            abort('403', 'У Вас недостаточно прав!');
        }
        $this->title = 'Добавление статьи';
        $categories = Category::select(['title','alias','parentId','id'])->get();

        $lists = array();

        foreach($categories as $category) {
            if($category->parentId == 0) {
                $lists[$category->title] = array();
            }
            else {
                $lists[$categories->where('id',$category->parentId)->first()->title][$category->id] = $category->title;
            }
        }

        $this->content = view(env('THEME').'.admin.articles_create_content')->with('categories', $lists)->render();
        return $this->renderOutput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {

      $result = $this->articles_repa->addArticle($request);

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
    public function edit(Article $article)
    {
        //$article  = Article::where('alias', $alias)->first();
        if(Gate::denies('edit', new Article)){
            abort('403', 'У Вас недостаточно прав!');
        }

        $this->title = 'Редактирование статьи '.$article->name ;
        $article->img = json_decode( $article->img);
        $categories = Category::select(['title','alias','parentId','id'])->get();

        $lists = array();

        foreach($categories as $category) {
            if($category->parentId == 0) {
                $lists[$category->title] = array();
            }
            else {
                $lists[$categories->where('id',$category->parentId)->first()->title][$category->id] = $category->title;
            }
        }

        $this->content = view(env('THEME').'.admin.articles_create_content')->with(['categories'=> $lists, 'article' => $article ])->render();
        return $this->renderOutput();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
       // $article = Article::where('alias', $alias)->first();
        $result = $this->articles_repa->updateArticle($request, $article);

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
    public function destroy(Article $article)
    {

        $result = $this->articles_repa->deleteArticle($article);

        if(is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }
}
