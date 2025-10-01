<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index(){
        return view('posts.index', [
            // 'posts' => Post::all()
             'posts' => Post::with('author') -> get()
        ]);
    }

    public function show(Post $post){
        return view('posts.show', ['post' => $post]);
    }
}
