<?php

namespace Corp;

use Illuminate\Database\Eloquent\Model;

class Portfoliocategory extends Model
{
    protected $table = 'portfolioCategories';

    public function portfolios(){
        $this->hasMany('Corp\Portfolio', 'alias', 'category');
    }
}
