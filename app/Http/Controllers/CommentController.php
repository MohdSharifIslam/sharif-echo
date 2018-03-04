<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;
use App\Post;
use App\Comment;
use App\Events\NewComment;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments()->with('user')->latest()->get();
        $response = [];
        foreach($comments as $comment){
            $response[] =  [
            'body' => $comment->body,
            'created_at' => $comment->created_at->toFormattedDateString(),
            'user' => [
                'name' => $comment->user->name,
                'avatar' => 'http://lorempixel/50/50',
                'id' => $comment->user->id
                ]
            ];
        }
        
        return response()->json($response);
    }

    public function store(Request $request, Post $post)
    {
        $comment = $post->comments()->create([
            'body' => $request->body,
            'user_id' => Auth::id()
        ]);
        
        $comment = Comment::find($comment->id)->with('user')->latest()->first();
        
        broadcast(new NewComment($comment))->toOthers();

        return $comment->toJson();
        // return response()->json($comment);
    }
}
