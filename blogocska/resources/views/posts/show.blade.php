@extends('default')

@section('title', $post -> title)

@section('content')

<h1 class="text-xl">{{ $post -> title }}</h1>
<i>{{ $post -> author -> name }}</i><br>

{{ $post -> content }}

@if ($post -> image !== null)
    <img src="{{ Storage::disk('public') -> url('images/' . $post -> image) }}">
@endif

@can('update', $post)
<br>
<a href="{{ route('posts.edit', ['post' => $post]) }}">Szerkesztés</a>
@endcan

@can('delete', $post)
<br>
<form action="{{ route('posts.destroy', ['post' => $post ]) }}" method="post">
    @csrf
    @method('DELETE')
    <a class="text-red-500" href="#" onclick="this.closest('form').submit();"">Törlés</a>
</form>
@endcan

@endsection
