<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\PostStoreOrUpdateRequest;

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

    public function store(PostStoreOrUpdateRequest $request){
        $validated = $request -> validated();
        $validated['is_public'] = $request -> has('is_public');
        $post = Post::create($validated);
        $post -> categories() -> sync($validated['categories'] ?? []);
        Session::flash('create-success', $post -> title);
        return redirect() -> route('posts.index');
    }

     public function edit(Post $post){
        return view('posts.edit', [
            'categories' => Category::all(),
            'users' => User::all(),
            'post' => $post
        ]);
    }

    public function update(PostStoreOrUpdateRequest $request, Post $post){
        $validated = $request -> validated();
        $validated['is_public'] = $request -> has('is_public');
        $post -> update($validated);
        $post -> categories() -> sync($validated['categories'] ?? []);
        Session::flash('update-success', $post -> title);
        return redirect() -> route('posts.index');
    }
}
