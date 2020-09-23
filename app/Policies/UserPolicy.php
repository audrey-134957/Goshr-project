<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Grant all abilities to administrator.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->role_id !== NULL) {
            return true;
        }
    }

     /**
     * Determine whether the user can create topic.
     *
     * @return mixed
     */
    public function create()
    {
            return true;
    }

     /**
     * Determine whether the user can update topic.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Topic  $topic
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->id === $;
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
     * 
     * Determine whether the user can report the topic.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Topic  $topic
     * @return mixed
     */
    public function doReport(User $user)
    {
        return $user->id !== $topic->user_id;
    }
}
}