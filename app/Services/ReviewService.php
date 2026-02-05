<?php

namespace App\Services;

use App\Models\Review;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReviewService
{
    use AuthorizesRequests;

    public function getReviewById($reviewId)
    {
        return Review::findOrFail($reviewId);
    }

    public function getReviewByCourse(int $courseId)
    {

        $user = auth()->user();

        if ($user->hasRole('Student')) {
            return Review::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->firstOrFail();
        }

        return Review::where('course_id', $courseId)->firstOrFail();
    }

    public function getAllReviews(): Collection
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            return Review::with(['user', 'course'])->latest()->get();
        }

        if ($user->hasRole('Instructor')) {

            return Review::whereHas('course', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            })->with(['user', 'course'])->latest()->get();
        }

        return Review::where('user_id', $user->id)->with('course')->latest()->get();
    }


    public function createReview(array $data)
    {
        try {
            return Review::create([
                'user_id'   => Auth::id(),
                'course_id' => $data['course_id'],
                'rating'    => $data['rating'],
                'comment'   => $data['comment'] ?? null,
            ]);
        } catch (Exception $e) {
            throw new Exception("Failed to create review. Please try again later.");
        }
    }


    public function updateReview(Review $review, array $data)
    {
        try {
            $review->update($data);
            return $review;
        } catch (\Exception $e) {
            throw new \Exception("Failed to update the review.");
        }
    }

    
    public function deleteReview($reviewId)
    {
        try {
            $review = $this->getReviewById($reviewId);

            $this->authorize('delete', $review);

            return  $review->delete();

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("Review Not Found");
        } catch (AuthorizationException $e) {
            throw new AuthorizationException('You are not authorized to perform this action');
        }
    }
}
