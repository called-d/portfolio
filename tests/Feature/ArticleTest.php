<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_article_index()
    {
        $article = Article::factory()->create();

        $response = $this->get('/');
        $response->assertStatus(200);
        // 下書きは見えない
        $response->assertDontSee($article->title);

        // 公開する
        $article->publish();

        $response = $this->get('/');
        // 公開したら見える
        $response->assertSee($article->title);
    }
}
