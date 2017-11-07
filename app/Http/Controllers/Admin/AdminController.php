<?php

namespace Corp\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Corp\Http\Controllers\Controller as Controller;
use Auth;
use Menu;
use Gate;
class AdminController extends  Controller
{
    protected $portfolio_repa;
    protected $articles_repa;
    //protected $menus_repa;

    ///protected $comments_repa;
    protected $user;
    protected $template;
    protected $content = false;
    protected $title;
    protected $data = array();

    public function __construct()
    {
     /*
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
        dd( $this->user);
     */
    }
    public function checkUser() {

       $this->user = Auth::user();
        if(!$this->user) {
            abort(403);
        }

    }

    public function renderOutput(){


        $this->data = array_add($this->data, 'title', $this->title);

        $menu = $this->getMenu();

        $navigation = view(env('THEME').'.admin.navigation')->with('menu', $menu)->render();
        $this->data = array_add($this->data, 'navigation', $navigation);

        if($this->content){
            $this->data = array_add($this->data, 'content', $this->content);
        }
        $footer = view(env('THEME').'.admin.footer')->render();
        $this->data = array_add($this->data,'footer',$footer);

        return view($this->template)->with($this->data);

    }
    public function getMenu(){

        return Menu::make('adminMenu', function ($menu){


            if(Gate::allows('VIEW_ADMIN_ARTICLES')){
                $menu->add('Статьи',array('route' => 'admin.articles.index'));
            }

            //$menu->add('Портфолио',  array('route'  => 'admin.articles.index'));
            if(Gate::allows('VIEW_ADMIN_MENU')) {
                $menu->add('Меню', array('route' => 'admin.menu.index'));
            }
            if(Gate::allows('EDIT_USERS')) {
                $menu->add('Пользователи', array('route' => 'admin.users.index'));
                $menu->add('Привилегии', array('route' => 'admin.permissions.index'));
            }

        });
    }
}
