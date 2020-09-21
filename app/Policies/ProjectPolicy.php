<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Project;

class ProjectPolicy
{
    use HandlesAuthorization;

    
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {

        if ($user->role_id == 1 || $user->role_id ==  2) {
            return true;
        }
    }


    public function create()
    {
        if (auth()->check() && auth()->user()->role_id == NULL) {
            return true;
        }
    }

    /**
     * Determine whether the user can show the model.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function show(User $user, Project $project)
    {
        return true;
    }

    /**
     * Determine whether the user can edit the model.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function edit(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }


    /**
     * Determine whether the user can  destroy the model.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function destroy(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }



    public function doReport(User $user, Project $project)
    {

        return $user->id !== $project->user_id;
    }
}
