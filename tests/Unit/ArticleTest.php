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

        $this->assertNull(Article::published()->find($article->id), 'default article is private.');

        $article->publish();

        $this->assertNotNull(Article::published()->find($article->id), 'published article is published');
    }
}
