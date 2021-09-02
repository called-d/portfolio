<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
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
        Auth::login(User::where('is_admin', false)->firstOrFail());

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

        // 下書きをもう一つ作る
        $article = Article::factory()->create();
        // 下書きは見えない
        $this->get('/')->assertDontSee($article->title);
        Auth::login(User::where('is_admin', true)->firstOrFail());
        // 管理者には見える
        $this->get('/')->assertSee($article->title);
    }
}
