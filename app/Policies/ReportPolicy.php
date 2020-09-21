<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function create()
    {
        if(auth()->check() && auth()->user()->role_id === NULL){
            return true;
        }
    }


}
