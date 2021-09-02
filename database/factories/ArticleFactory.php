<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(3);
        return [
            'slug' => Str::snake($title, '-'),
            'title' => Str::title($title),
            'content' => $this->faker->sentence(20),
        ];
    }

    /**
     * @param \DateTime|string|null $at
     */
    public function published($at = null): ArticleFactory {
        return $this->state(fn (array $attributed) => [
            'published_at' => Date::parse($at),
        ]);
    }

    public function unpublish(): ArticleFactory {
        return $this->state(fn () => ['published' => null]);
    }
}
