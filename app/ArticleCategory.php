<?php

namespace Corp;

use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    protected $table = 'articlecategories';

    public function articles(){
        $this->hasMany('Corp\Article');
    }
}
