<?php

namespace App\Policies;

use App\Enums\EnrollmentStatus;
use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReviewPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Review $review): bool
    {

        if ($user->hasRole('Admin')) return true;


        if ($user->hasRole('Instructor')) {
            return $review->course->instructor_id === $user->id;
        }

        
        if ($user->hasRole('Student')) {
            return $review->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, int $courseId): bool
    {


        if (!$user->hasRole('Student')) {
            return false;
        }


        return $user->enrolledCourses()->where('course_id', $courseId)->whereIn('course_user.status', [
            EnrollmentStatus::ACTIVE->value,
            EnrollmentStatus::COMPLETED->value
        ])->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Review $review): bool
    {
        return $user->hasRole('Student') && $user->id === $review->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Review $review): bool
    {
        if ($user->hasRole('Admin')) return true;

        if ($user->hasRole('Student') && $user->id === $review->user_id) return true;

        if ($user->hasRole('Instructor') && $user->id === $review->course->instructor_id) return true;

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Review $review): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Review $review): bool
    {
        return false;
    }
}
