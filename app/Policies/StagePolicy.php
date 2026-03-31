<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StagePolicy
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
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Stage $stage)
    {
        $project = $stage->project;

        if (! $project instanceof Project) {
            return false;
        }

        if ($user->hasRole('Mentor')) {
            return (int) $project->mentor_id === (int) $user->id;
        }

        return (int) $project->entrepreneur_id === (int) $user->id;
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
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Stage $stage)
    {
        return $user->hasRole('Mentor') && (int) optional($stage->project)->mentor_id === (int) $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Stage $stage)
    {
        return $this->update($user, $stage);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Stage $stage)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Stage $stage)
    {
        //
    }
}
