@extends('layouts.base')

@push('styles')
<style>
    .card-body-link {
        position: absolute;
        right: 0;
        bottom: 0;
        line-height: 1;
        padding: .5em;
        display: none;
    }
    .selected .card-body-link {
        display: inline;
    }
</style>
@endpush

@section('content')
<div id="app">
    <portfolio-slider :articles='@json($articles)'>
        <template v-slot="{ article }">
            <h4>@{{ article.title }}</h4>
            <div>@{{ article.content }}</div>
        </template>
        <template v-slot:card_ui="{ article }">
            <a class="card-body-link" :href="`#fallback__${article.slug}`">▼</a>
        </template>
    </portfolio-slider>
</div>

<!-- フォールバック用非js -->

@foreach($articles as $article)
    <article class="portfolio_article large_card" id="fallback__{{ $article->slug }}">
        <h4>{{ $article->title }}</h4>
        <div>{{ $article->content }}</div>
    </article>
@endforeach
@endsection
