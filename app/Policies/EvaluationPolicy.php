<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EvaluationPolicy
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
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Evaluation $evaluation)
    {
        $project = optional(optional(optional(optional($evaluation->submission)->task)->stage)->project);

        if (! $project instanceof Project) {
            return false;
        }

        if ($user->hasRole('Mentor')) {
            return (int) $project->mentor_id === (int) $user->id;
        }

        return (int) optional($evaluation->submission)->submitted_by === (int) $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasRole('Mentor');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Evaluation $evaluation)
    {
        $project = optional(optional(optional(optional($evaluation->submission)->task)->stage)->project);

        return $user->hasRole('Mentor') && $project && (int) $project->mentor_id === (int) $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Evaluation $evaluation)
    {
        return $this->update($user, $evaluation);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Evaluation $evaluation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Evaluation $evaluation)
    {
        //
    }
}
