<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Project;

class ProjectPolicy
{
    use HandlesAuthorization;



    /**
     * Determine whether the user can create project.
     *
     */
    public function create()
    {
        if (auth()->check() && auth()->user()->role_id == NULL) {
            return true;
        }
    }

    /**
     * Determine whether the user can show project.
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
     * Determine whether the user can edit project.
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
     * Determine whether the user can update project.
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
     * Determine whether the user can  destroy project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function destroy(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine whether the user can report project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function doReport(User $user, Project $project)
    {

        return $user->id !== $project->user_id;
    }
}
