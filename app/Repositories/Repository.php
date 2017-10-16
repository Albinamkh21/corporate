<?php
namespace Corp\Repositories;
use Config;



abstract class Repository {

    protected $model= FALSE;

    public function get($select = '*', $take = FALSE , $pagination = false, $where = false){

        $builder = $this->model->select($select);
        if($take){
            $builder->take($take);
        }
        if($where){
            $builder->where($where[0],$where[1]);
        }
        if($pagination){
            return $this->check($builder->paginate(Config::get('settings.pagination')));
        }
        return $this->check($builder->get());
    }
    public function getOne($select = '*', $where = false)
    {

        $builder = $this->model->select($select);
        if ($where) {
            $builder->where($where[0],$where[1]);
        }
        $result  = $builder->first();
        if (is_string($result->img) && is_object(json_decode($result->img)) && json_last_error() == JSON_ERROR_NONE) {
            $result->img = json_decode($result->img);
        }
        return $result;
    }
    protected function check($result)
    {
        if ($result->isEmpty()) {
            return FALSE;
        }
        $result->transform(function ($item, $key) {
           if (is_string($item->img) && is_object(json_decode($item->img)) && json_last_error() == JSON_ERROR_NONE) {
                $item->img = json_decode($item->img);
           }
             return $item;
        });

        return $result;
    }

}

?>