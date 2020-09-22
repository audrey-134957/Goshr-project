<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create topic.
     *
     * @return mixed
     */
    public function create()
    {
        if(auth()->check()){
            return true;
        }
    }

    /**
     * Determine whether the user can update topic.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Topic  $topic
     * @return mixed
     */
    public function update(User $user, Topic $topic)
    {
        return $user->id === $topic->user_id;
    }

    /**
     * Determine whether the user can answer to topic.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Topic  $topic
     * @return mixed
     */
    public function answerToTopic(User $user, Topic $topic)
    {
        return $user->id === $topic->user_id || $user->id === $topic->topicable->user_id;
    }

    /**
     * Determine whether the user can report the topic.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Topic  $topic
     * @return mixed
     */
    public function doReport(User $user, Topic $topic)
    {
        return $user->id !== $topic->user_id;
    }
}
