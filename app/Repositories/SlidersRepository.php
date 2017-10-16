<?php
namespace Corp\Repositories;
use Corp\Slider;
use Corp\Repositories\Repository;

class SlidersRepository extends Repository {

    public function __construct(Slider $slider)
    {
        $this->model = $slider;
    }


}

?>/