<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Resources\CategoryResource;

class ApiController extends Controller
{
    public function login(Request $request){
        $validated = $request -> validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]); // 422
        if (Auth::attempt($validated)){
            $user = User::where('email', $validated['email']) -> first();
            $token = $user -> createToken('loginToken');
            return response()->json([ 'token' => $token -> plainTextToken ]);
        } else {
            return response()->json([ 'message' => 'Nem.' ], 401);
        }
    }

    public function index(){
        $posts = Post::all();
        return PostResource::collection($posts);
    }

    public function show(string $post){
        // if (filter_var($post, FILTER_VALIDATE_INT) === false){
        //     return response() -> json(["message" => 'post must be an integer!'], 422);
        // }

        validator(
            ['post' => $post],
            ['post' => 'required|integer']
        ) -> validate();

        // $post = Post::find($post);
        // if ($post)
        //     return new PostResource($post);
        // else
        //     return response() -> json(["message" => 'Post not found!'], 404);

        $post = Post::findOrFail($post);
        return new PostResource($post);
    }

    public function store(Request $request){
        $validated = $request -> validate([
            'title' => 'required|string',
            'content' => 'required|string|min:10',
            'is_public' => 'required|boolean'
        ]);
        $validated['author_id'] = $request -> user() -> id;
        $post = Post::create($validated);
        return new PostResource($post);
    }

    public function indexCategories(string $post){
        validator(
            ['post' => $post],
            ['post' => 'required|integer']
        ) -> validate();

        $post = Post::findOrFail($post);

        return CategoryResource::collection($post -> categories);
    }

    public function indexWithCategories(){
        $posts = Post::with('categories') -> get();
        return PostResource::collection($posts);
    }

    public function updateCategories(Request $request, string $post){
        validator(
            ['post' => $post],
            ['post' => 'required|integer']
        ) -> validate();

        $post = Post::findOrFail($post);

        $validated = $request -> validate([
            "add" => "array",
            "remove" => "array",
            "add.*" => "distinct|integer|exists:categories,id",
            "remove.*" => "distinct|integer|exists:categories,id",
        ]);
        //$start = $post -> categories -> pluck('id') -> toArray();
        //$to_add = array_diff($validated["add"] ?? [], $start);
        //$post -> categories() -> attach($to_add);
        $post -> categories() -> syncWithoutDetaching($validated["add"] ?? []);
        $post -> categories() -> detach($validated["remove"] ?? []);

        return CategoryResource::collection($post -> categories); // alternativa: ["added", "was already added", "removed", "was already removed"]
    }
}
