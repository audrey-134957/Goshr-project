<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Motive;
use App\Models\Project;
use App\Models\Topic;
use App\Traits\ProjectTrait;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{

    use ProjectTrait;

    /**
     * Show the comment's notification to user.
     *
     * @param int $project | id of the project
     * @param  Illuminate\Notifications\DatabaseNotification 
     * @param int $notification | id of the notification
     * @return \Illuminate\Http\Response
     */
    public function showCommentFromNotification($project, DatabaseNotification $notification)
    {

        $commentId = $notification['data']['commentId'];
        $commentFounded = Comment::find($commentId);
        
        $commentReplyId = $notification['data']['commentReplyId'];
        $commentReplyFounded = Comment::find($commentReplyId);

        if ($commentFounded == NULL || $commentReplyFounded) {
            $notification->delete();

            return back();
        }

        // je récupère le projet
        $project = Project::with('materials')->where('status_id', 2)->findOrFail($project);
        //je stoke le slug en variable
        $slug = $project->slug;
        // je récupère les motives
        $motives = Motive::all();
        //je notifie l'auteur du projet qu'une personne a commenté celui-ci
        $notification->markAsRead();

        // je récupère les projets de suggestion
        $projects =  Project::with('category', 'user', 'materials', 'difficulty_level', 'unity_of_measurement')
            ->where('status_id', 2)
            ->whereNotIn('id', [$project->id])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view(
            'projects.show',
            [
                'project' => $project,
                'slug' => $slug,
                'motives' => $motives,
                'difficultyClassName' => $this->giveTheProjectDifficultyLevel($project),
                'projects' => $projects
            ]
        );
    }

    /**
     * Show the comment's notification to user.
     *
     * @param int $project | id of the project
     * @param  Illuminate\Notifications\DatabaseNotification 
     * @param int $notification | id of the notification
     * @return \Illuminate\Http\Response
     */
    public function showTopicFromNotification($project, DatabaseNotification $notification)
    {

        $topicId = $notification['data']['topicId'];
        $topicFounded = Topic::find($topicId);

        $topicReplyId = $notification['data']['topicReplyId'];
        $topicReplyFounded = Topic::find($topicReplyId);

        if ($topicFounded == NULL || $topicReplyFounded) {
            $notification->delete();

            return back();
        }

        // je récupère le projet
        $project = Project::with('materials')->where('status_id', 2)->findOrFail($project);
        //je stoke le slug en variable
        $slug = $project->slug;
        // je récupère les motives
        $motives = Motive::all();
        //je notifie l'auteur du projet qu'une personne a laissé un topic sur celui-ci
        $notification->markAsRead();


        // je récupère les projets de suggestion
        $projects =  Project::with('category', 'user', 'materials', 'difficulty_level', 'unity_of_measurement')
            ->where('status_id', 2)
            ->whereNotIn('id', [$project->id])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view(
            'projects.show',
            [
                'project' => $project,
                'slug' => $slug,
                'motives' => $motives,
                'difficultyClassName' => $this->giveTheProjectDifficultyLevel($project),
                'projects' => $projects
            ]
        );
    }
}
