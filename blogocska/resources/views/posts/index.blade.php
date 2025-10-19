@extends('default')

@section('title', 'Kezdőlap')

@section('content')

@if (Session::has('update-success'))
    <div class="my-2 rounded bg-green-300 text-center">
        A(z) {{ Session::get('update-success') }} bejegyzés szerkesztése sikeres!
    </div>
@elseif (Session::has('create-success'))
    <div class="my-2 rounded bg-green-300 text-center">
        A(z) {{ Session::get('create-success') }} bejegyzés létrehozása sikeres!
    </div>
@elseif (Session::has('delete-success'))
    <div class="rounded my-2 bg-green-300">A(z) {{ Session::get('delete-success') }} bejegyzés sikeresen törölve.</div>
@endif

@can('create', \App\Models\Post::class)
<a class="text-green-500" href="{{ route('posts.create') }}">Új bejegyzés írása</a>
<br><br>
@endcan

<ul>
    @foreach($posts as $post)
        <li><a href="{{ route('posts.show', ['post' => $post ]) }}">{{ $post -> title }}</a>
        <i> ({{ $post -> author -> name }})</i>
        </li>
    @endforeach
</ul>

{{ $posts -> links() }}

@endsection
