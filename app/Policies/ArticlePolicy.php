<?php

namespace Corp\Policies;

use Corp\User;
use Corp\Article;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function save(User $user){
        return $user->canDo('ADD_ARTICLE');
    }
    public function edit(User $user){
        return $user->canDo('EDIT_ARTICLE');
    }
    public function destroy(User $user, Article $article){

        return ($user->canDo('DELETE_ARTICLE') && $user->id == $article->userId);
    }

}
