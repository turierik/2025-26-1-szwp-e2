@extends('default')

@section('title', $post -> title)

@section('content')

<h1 class="text-xl">{{ $post -> title }}</h1>
<i>{{ $post -> author -> name }}</i><br>

{{ $post -> content }}

@endsection
