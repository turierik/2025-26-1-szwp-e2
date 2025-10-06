<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

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

    public function create(){
        return view('posts.create', [
            'categories' => Category::all(),
            'users' => User::all()
        ]);
    }

    public function store(Request $request){
        $validated = $request -> validate([
            'title' => 'required|string',
            'content' => 'required|string|min:10',
            'author_id' => 'required|integer|exists:users,id',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|distinct|exists:categories,id'
        ], [
            'content.min' => 'A tartalom legalább 10 karakter kell legyen!'
        ]);
        $validated['is_public'] = $request -> has('is_public');
        $post = Post::create($validated);
        $post -> categories() -> sync($validated['categories'] ?? []);
        return redirect() -> route('posts.index');
    }
}
