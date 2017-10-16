<?php
namespace Corp\Repositories;
use Corp\Menu;
use Corp\Repositories\Repository;

class MenusRepository extends Repository {

    public function __construct(Menu $menu)
    {
        $this->model = $menu;
    }

}

?>