<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class LatestNews extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title', 'content', 'order', 'is_active'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('news_images')
             ->singleFile(); 
    }
}
