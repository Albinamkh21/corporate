<?php

namespace Corp;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title','img','alias','text','desc','keywords','meta_desc','category_id'];
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
