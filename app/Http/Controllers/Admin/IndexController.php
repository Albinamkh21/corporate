<?php

namespace Corp\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Corp\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Gate;

class IndexController extends AdminController
{
    public function __construct()
    {
         parent ::__construct();
         $this->template = env('THEME').'.admin.index';
    }

    public function index() {
        $this->checkUser();
        if(Gate::denies('VIEW_ADMIN', $this->user)){
            abort(403, "У Вас недостаточно прав!");
        }

        $this->title = 'Панель администратора';
        return $this->renderOutput();

    }


}
