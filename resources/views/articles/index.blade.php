index
@foreach($articles as $article)
    <article>
        <h2>{{ $article->title }}</h2>
        <div>
            {{ $article->content }}
        </div>
    </article>
@endforeach
