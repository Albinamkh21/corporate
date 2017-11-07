<?php
namespace Corp\Repositories;
use Corp\Menu;
use Corp\Repositories\Repository;
use Gate;

class MenusRepository extends Repository {

    public function __construct(Menu $menu)
    {
        $this->model = $menu;
    }
    public function addMenu($request) {
        if(Gate::denies('save', $this->model)) {
            abort(403, 'Недостаточно прав для добавления меню');
        }

        $data = $request->only('type','title','parentId');

        if(empty($data)) {
            return ['error'=>'Нет данных'];
        }

       //dd($request->all());

        switch($data['type']) {

            case 'customLink':
                $data['path'] = $request->input('custom_link');
                break;

            case 'blogLink' :

                if($request->input('category_alias')) {
                    if($request->input('category_alias') == 'parent') {
                        $data['path'] = route('articles.index');
                    }
                    else {
                        $data['path'] = route('articles_category',['category'=>$request->input('category_alias')]);
                    }
                }

                else if($request->input('article_alias')) {
                    $data['path'] = route('articles.show',['alias' => $request->input('article_alias')]);
                }

                break;

            case 'portfolioLink' :
                if($request->input('filter_alias')) {
                    if($request->input('filter_alias') == 'parent') {
                        $data['path'] = route('portfolio.index');
                    }
                }

                else if($request->input('portfolio_alias')) {
                    $data['path'] = route('portfolio.show',['alias' => $request->input('portfolio_alias')]);
                }
                break;

        }


        unset($data['type']);

        if($this->model->fill($data)->save()) {
            return ['status'=>'Ссылка добавлена'];
        }



    }

    public function updateMenu($request, $menu) {
        if(Gate::denies('save', $this->model)) {
            abort(403);
        }

        $data = $request->only('type','title','parentId');

        if(empty($data)) {
            return ['error'=>'Нет данных'];
        }

        //dd($request->all());

        switch($data['type']) {

            case 'customLink':
                $data['path'] = $request->input('custom_link');
                break;

            case 'blogLink' :

                if($request->input('category_alias')) {
                    if($request->input('category_alias') == 'parent') {
                        $data['path'] = route('articles.index');
                    }
                    else {
                        $data['path'] = route('articles_category',['category'=>$request->input('category_alias')]);
                    }
                }

                else if($request->input('article_alias')) {
                    $data['path'] = route('articles.show',['alias' => $request->input('article_alias')]);
                }

                break;

            case 'portfolioLink' :
                if($request->input('filter_alias')) {
                    if($request->input('filter_alias') == 'parent') {
                        $data['path'] = route('portfolio.index');
                    }
                }

                else if($request->input('portfolio_alias')) {
                    $data['path'] = route('portfolio.show',['alias' => $request->input('portfolio_alias')]);
                }
                break;

        }


        unset($data['type']);

        if($menu->fill($data)->update()) {
            return ['status'=>'Ссылка обновлена'];
        }



    }

    public function deleteMenu($menu) {
        if(Gate::denies('save', $this->model)) {
            abort(403);
        }

        if($menu->delete()) {
            return ['status'=>'Ссылка удалена'];
        }
    }


}

?>