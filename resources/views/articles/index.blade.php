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
    .portfolio_article.card h2.card_title {
        font-size: 1.2rem;
    }
    .portfolio_article.large_card {
        max-width: 900px;
        margin: auto;
        margin-bottom: 5em;
        padding: 0 1em;
        overflow: hidden;
    }
    .portfolio_article.large_card a {
        color: revert;
        text-decoration: revert;
    }
</style>
@endpush

@section('content')
<div id="app">
    <portfolio-slider :articles='@json($articles)'>
        <template v-slot="{ article }">
            <h2 class="card_title">@{{ article.title }}</h2>
            <div>@{{ article.content }}</div>
        </template>
        <template v-slot:card_ui="{ article }">
            <a class="card-body-link" :href="`#fallback__${article.slug}`">▼</a>
        </template>
    </portfolio-slider>

    @foreach($articles as $article)
    <article class="portfolio_article large_card" id="fallback__{{ $article->slug }}" v-once>
        <h2>{{ $article->title }}</h2>
        @isset($article->metadata['script']){!! $article->metadata['script'] !!}@endisset
        @isset($article->metadata['md'])
            <div class="article_content" data-is-md v-once><pre>{{ $article->content }}</pre></div>
        @else
            <div class="article_content">{{ $article->content }}</div>
        @endisset
    </article>
    @endforeach
</div>
@endsection
