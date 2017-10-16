<?php

namespace Corp;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['text', 'name', 'email', 'site', 'parentId', 'created_at', 'updated_at', 'userId', 'articleId'];
    public function article(){
       return $this->belongsTo('Corp\Article', 'articleId', 'id');
    }
    public function user(){
        return $this->belongsTo('Corp\User', 'userId', 'id');
    }
}
