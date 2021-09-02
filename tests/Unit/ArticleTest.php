<?php

namespace Tests\Unit;

use App\Models\Article;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_publish()
    {
        $article = Article::factory()->create();

        $this->assertNotNull(Article::draft()->find($article->id), 'just created article is draft');
        $this->assertNull(Article::published()->find($article->id), 'default article is private.');

        $article->publish();

        $this->assertNull(Article::draft()->find($article->id), 'published article is not draft.');
        $this->assertNotNull(Article::published()->find($article->id), 'published article is published');
    }
}
