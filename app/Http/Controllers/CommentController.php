<?php

namespace Corp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Corp\Comment;
use Corp\Article;
class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token','comment_post_ID', 'comment_parent');
        $data['articleId'] = $request->input('comment_post_ID');
        $data['parentId'] = $request->input('comment_parent');

        $validator = Validator::make($data, [
           'articleId' => 'integer|required',
           'parentId' => 'integer|required',
           'text' => 'string|required',
        ]);
        $validator->sometimes(['name', 'email'], 'required|max:255',function ($index){
            return !Auth::check();
        });

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $user = Auth::user();
        $comment = new Comment($data);
        if($user){
            $comment->userId = $user->id;
        }
        $article = Article::find($data['articleId']);
        $article->comments()->save($comment);

        $comment->load('user');
        $data['id'] = $comment->id;
        $data['name'] = (!empty($data['name']))? $data['name'] : $comment->user->name;
        $data['email'] = (!empty($data['email']))? $data['email'] : $comment->user->email;
        $data['hash'] = md5($data['email']);

        $view_comment = view(env('THEME').'.blog_one_comment')->with('data', $data)->render();
        return response()->json(['success' => true, 'comment'=>$view_comment, 'data' => $data ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
