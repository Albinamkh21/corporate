<?php

namespace Corp;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function user(){
        return $this->belongsTo('Corp\User', 'userId', 'id');
    }
    public function category(){
        return $this->belongsTo('Corp\ArticleCategory', 'categoryId', 'id');
    }
    public function comments(){
        return $this->hasMany('Corp\Comment', 'articleId','id' );
    }
}
