@extends('layouts.base')

@section('content')
<div id="app">
    <portfolio-slider :articles='@json($articles)'></portfolio-slider>
</div>

<!-- フォールバック用非js -->

@foreach($articles as $article)
    <article>
        <h4>{{ $article->title }}</h4>
        <div>{{ $article->content }}</div>
    </article>
@endforeach
@endsection
