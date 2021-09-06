<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'metadata' => '{
        }',
    ];

    protected $appends = [
        'thumbnail_url',
    ];

    public function getThumbnailUrlAttribute()
    {
        return optional($this->thumbnail_path, fn($path) => Storage::url($path));
    }

    //==== 公開/非公開

    /**
     * @param mixed|null $on
     */
    public function publish($on = null)
    {
        $this->update(['published_at' => Date::parse($on)]);
    }

    /**
     * @param mixed $on いつの時点で公開？　str, datetime or null
     */
    public function scopePublished(Builder $query, $on = null)
    {
        return $query->where('published_at', '<=', Date::parse($on));
    }

    public function scopeDraft(Builder $query, $on = null)
    {
        return $query->whereNull('published_at')->orWhere('published_at', '>', Date::parse($on));
    }
}
