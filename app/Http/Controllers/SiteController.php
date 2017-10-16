<?php
/*
 * Родительский контроллер пользовательской части, те самого сайта
 */
namespace Corp\Http\Controllers;

use Illuminate\Http\Request;
use Corp\Repositories\MenusRepository;
use Menu;

class SiteController extends Controller
{
    protected $portfolio_repa;
    protected $service_repa;
    protected $articles_repa;
    protected $menus_repa;
    protected $sliders_repa;
    protected $comments_repa;

    protected $template;

    protected $sidebar = 'no';
    protected $contentRightBar = FALSE;
    protected $contentLeftBar = FALSE;


    protected $keywords = '';
    protected $meta_desc;
    protected $title;

    protected $data = array();

    public function __construct(MenusRepository $menus_repa)
    {
        $this->menus_repa = $menus_repa;
    }
    //возвращает втюху темплейта главной старницы
    protected function renderOutput(){

        //меню есть на всех страницах, поэтому в родительком контролере
        $menu = $this->getMenu();

       // dd($menu);
        $navigation = view(env('THEME').'.navigation')->with('menu', $menu)->render( );
        $this->data = array_add($this->data, 'navigation', $navigation);
        if($this->contentRightBar){
          $sidebarRight = view(env('THEME').'.sidebar_right')->with('sidebar_right_content',$this->contentRightBar)->render();
          $this->data = array_add($this->data, 'sidebar_right', $sidebarRight);
        }
        $this->data = array_add($this->data, 'sidebar', $this->sidebar);

        $footer = view(env('THEME').'.footer')->render();
        $this->data = array_add($this->data, 'footer',$footer);

        $this->data = array_add($this->data, 'keywords',$this->keywords);
        $this->data = array_add($this->data, 'meta_desc',$this->meta_desc);
        $this->data = array_add($this->data, 'title',$this->title);


        return view($this->template)->with($this->data);
    }
    protected function getMenu()
    {

        $menu = $this->menus_repa->get();
        $menuBuilder = Menu::make('mainMenu', function($m) use ($menu) {
            foreach($menu as $item){
                if($item->parentId == 0){
                    $m->add($item->title,$item->path)->id($item->id);
                }
                else {
                    if($m->find($item->parentId)){
                        $m->find($item->parentId)->add($item->title, $item->path)->id($item->id);
                    }
                }

            }

        });
        return $menuBuilder;
    }
}
