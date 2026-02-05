<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Http\Resources\NewsResource;
use App\Models\LatestNews;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\NewsService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LatestNewsController extends Controller
{
    use AuthorizesRequests;

    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $news = $this->newsService->getAllNews();

            return response_success(
                NewsResource::collection($news),
                200,
                'Latest news retrieved successfully.'
            );
        } catch (\Exception $e) {
            return response_error(null, 500, 'Could not fetch news: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
        try {

            $this->authorize('create', LatestNews::class);


            $news = $this->newsService->storeNews($request->validated());


            return response_success(
                new NewsResource($news),
                201,
                'News published successfully.'
            );
        } catch (AuthorizationException $e) {
            return response_error(null, 403, 'You do not have permission to perform this action.');
        } catch (\Exception $e) {
            return response_error(null, 500, 'An error occurred while saving the news.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    try {
        $news = $this->newsService->getNewsById($id);

        return response_success(
            new NewsResource($news),
            200,
            'News details retrieved successfully.'
        );

    } catch (ModelNotFoundException $e) {
        return response_error(null, 404, $e->getMessage());
    } catch (\Exception $e) {
        return response_error(null, 500, 'Error: ' . $e->getMessage());
    }
}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, $id)
    {
        try {

            $this->authorize('update', LatestNews::class);

            $updatedNews = $this->newsService->updateNews($id, $request->validated());

            return response_success(new NewsResource($updatedNews), 200, 'News updated successfully');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, $e->getMessage());
        } catch (\Exception $e) {
            return response_error(null, 500, 'Error: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    // داخل LatestNewsController.php

    public function destroy($id)
    {
        try {

            $this->authorize('delete', LatestNews::class);

            $this->newsService->deleteNews($id);

            return response_success(null, 200, 'News deleted successfully');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, $e->getMessage());
        } catch (AuthorizationException $e) {
            return response_error(null, 403, 'You do not have permission to delete news.');
        } catch (\Exception $e) {
            return response_error(null, 500, 'Error during deletion: ' . $e->getMessage());
        }
    }
}
