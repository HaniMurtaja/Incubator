<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\TaskSubmission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskSubmissionPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['Admin', 'Mentor', 'Entrepreneur']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskSubmission  $taskSubmission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TaskSubmission $taskSubmission)
    {
        $project = optional(optional(optional($taskSubmission->task)->stage)->project);

        if (! $project instanceof Project) {
            return false;
        }

        if ($user->hasRole('Mentor')) {
            return (int) $project->mentor_id === (int) $user->id;
        }

        return (int) $taskSubmission->submitted_by === (int) $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasRole('Entrepreneur');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskSubmission  $taskSubmission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TaskSubmission $taskSubmission)
    {
        if (! $user->hasRole('Entrepreneur')) {
            return false;
        }

        if ((int) $taskSubmission->submitted_by !== (int) $user->id) {
            return false;
        }

        return ! in_array($taskSubmission->status, ['approved'], true);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskSubmission  $taskSubmission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TaskSubmission $taskSubmission)
    {
        return $this->update($user, $taskSubmission);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskSubmission  $taskSubmission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TaskSubmission $taskSubmission)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskSubmission  $taskSubmission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TaskSubmission $taskSubmission)
    {
        //
    }
}
