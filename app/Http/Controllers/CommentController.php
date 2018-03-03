<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;
use App\Post;
use App\Comment;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $response = $post->comments()->with('user')->latest()->get();
        return response()->json($response);
    }

    public function store(Request $request, Post $post)
    {
        $comment = $post->comments()->create([
            'body' => $request->body,
            'user_id' => Auth::id()
        ]);
        
        $comment = Comment::find($comment->id)->with('user')->latest()->first();

        return $comment->toJson();
        // return response()->json($comment);
    }
}
