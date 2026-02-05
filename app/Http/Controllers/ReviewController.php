<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\ReviewService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    protected $reviewService;


    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $reviews = $this->reviewService->getAllReviews();

            return response_success(
                ReviewResource::collection($reviews),
                200,
                'Reviews retrieved successfully.'
            );
        } catch (\Exception $e) {
            return response_error(null, 500, $e->getMessage());
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        try {
            $this->authorize('create', [Review::class, $request->course_id]);

            $review = $this->reviewService->createReview($request->validated());

            return response_success(new ReviewResource($review), 201, 'Review created successfully');
        } catch (AuthorizationException $e) {

             return response_error(null, 403, 'You are not authorized to perform this action');
        } catch (Exception $e) {
            return response_error(null, 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($reviewId)
    {
        try{
        $review = $this->reviewService->getReviewById($reviewId);

        $this->authorize('view', $review);
        return response_success(new ReviewResource($review));
    } catch (AuthorizationException $e) {
        return response_error(null, 403, 'You do not have permission to view this review.');
    } catch (ModelNotFoundException $e) {
        return response_error(null, 404, 'Review not found.');
    } catch (\Exception $e) {
        return response_error(null, 500, $e->getMessage());
    }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, $courseId)
    {
        try {

            $review = $this->reviewService->getReviewByCourse($courseId);


            $this->authorize('update', $review);


            $updatedReview = $this->reviewService->updateReview($review, $request->validated());

            return response_success(new ReviewResource($updatedReview), 200, 'Review updated successfully.');
        } catch (AuthorizationException $e) {
            return response_error(null, 403, 'You are not authorized to perform this action');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, 'Review not found.');
        } catch (\Exception $e) {
            return response_error(null, 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($reviewId)
    {
        try {
            $this->reviewService->deleteReview($reviewId);
            return response_success(null, 200, 'Review deleted successfully.');
        } catch (AuthorizationException $e) {
            return response_error(null, 403, 'You are not authorized to perform this action');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, 'Review not found.');
        }
    }
}
