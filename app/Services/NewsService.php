<?php

namespace App\Services;

use App\Models\LatestNews;
use App\Models\Review;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsService
{

    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }


    public function getNewsById($id)
    {
        $news = LatestNews::find($id);

        if (!$news) {
            throw new ModelNotFoundException("News item with ID $id not found.");
        }

        return $news;
    }

    public function getAllNews()
    {
        return LatestNews::where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }




    public function storeNews(array $data)
    {

        $news = LatestNews::create($data);


        if (isset($data['image'])) {
            $this->mediaService->uploadMultipleMedia($news, [$data['image']], 'news_images');
        }

        return $news;
    }

    public function updateNews($id, array $data): LatestNews
    {
        $news = $this->getNewsById($id);


        $news->update($data);


        if (isset($data['image'])) {
            $this->mediaService->uploadMultipleMedia($news, [$data['image']], 'news_images');
        }

        return $news;
    }


    public function deleteNews($id): bool
    {

        $news = $this->getNewsById($id);

        return $news->delete();
    }
}
