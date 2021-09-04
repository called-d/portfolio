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
    .portfolio_article.card {
        padding: 0 1em;
    }
    .portfolio_article.large_card {
        max-width: 900px;
        margin: auto;
        margin-bottom: 5em;
        padding: 0 1em;
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
