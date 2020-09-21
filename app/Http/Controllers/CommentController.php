<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Comment;

use App\Http\Requests\EditComment;
use App\Http\Requests\EditCommentReply;
use App\Http\Requests\StoreComment;
use App\Http\Requests\StoreCommentReply;
use App\Notifications\NewCommentPosted;
use App\Notifications\NewCommentReplyPosted;
use App\Notifications\SendMailToAuthorConcerningCommentDeletion;

class CommentController extends Controller
{

    /**
     * Store a new created comment in database.
     *
     * @param  \Illuminate\Http\Requests\StoreComment $request
     * @param int $project | id the project
     * @param string $slug | slug of the project
     * @return \Illuminate\Http\Response
     */
    public function store(StoreComment $request, $project, $slug)
    {
        //je récupère le projet
        $project = Project::where('status_id', 2)->findOrFail($project);
        //je récupère le slug du project publié en variable
        $slug = $project->slug;

        /* Store new comment */
        $comment = new Comment();
        $comment->content = purifier($request->comment_content);
        $comment->user_id = auth()->user()->id;
        $project->comments()->save($comment);
        //je notifie l'auteur du projet qu'un commentaire vient d'être posté
        if ($comment->user_id !== $project->user_id) {
            //l'auteur du projet est notifié
            $project->user->notify(new NewCommentPosted($comment, $project, auth()->user()));
        }
        //je redirige l'utilisateur vers le projet publié
        return redirect()->route('projects.show', compact('project', 'slug'));
    }

    /**
     * Update the comment in database.
     *
     * @param  \Illuminate\Http\Requests\EditComment  $request
     * @param int $project | id the project
     * @param string $slug | slug of the project
     * @param int $comment | id the comment
     * @return \Illuminate\Http\Response
     */
    public function update(EditComment $request, $project, $slug, $comment)
    {
        //je stocke le projet concerné en variable
        $project = Project::where('status_id', 2)->findOrFail($project);
        //je stocke le slug du projet concerné
        $slug = $project->slug;

        /* Edit comment */
        $comment = Comment::find($comment);
        $comment->content = purifier($request->edit_comment_content);
        $comment->save();

        //je redirige l'utilisateur vers le projet publié
        return redirect()->route('projects.show', compact('project', 'slug'));
    }

    /**
     * Store the comment reply in database.
     *
     * @param  \Illuminate\Http\Requests\StoreCommentReply $request
     * @param int $project | id the project
     * @param string $slug | slug of the project
     * @param int $comment | id the comment
     * @return \Illuminate\Http\Response
     */
    public function storeReply(StoreCommentReply $request, $project, $slug, $comment)
    {

        $comment = Comment::findOrFail($comment);
        //je récupère le projet
        $project = Project::where('status_id', '=', 2)->findOrFail($project);
        //je récupère le slug du project publié en variable
        $slug = $project->slug;


        /* Store comment reply */
        $commentReply = new Comment();
        $commentReply->content = purifier($request->comment_reply_content);
        $commentReply->user_id = auth()->user()->id;
        $comment->comments()->save($commentReply);

        //je récupère le dernier commentaire
        $lastReplyComment = $comment->comments()->orderBy('created_at', 'desc')->first();
        //si l'auteur de ce dernier commentaire est le même que l'auteur du projet
        if ($lastReplyComment->user_id === $project->user_id) {
            //je notifie l'auteur du commentaire
            $comment->user->notify(new NewCommentReplyPosted($commentReply, $project, auth()->user()));
        }
        //si l'auteur du dernier commentaire est différent de celui de l'auteur du projet
        if ($lastReplyComment->user_id !== $project->user_id) {
            //je notifie l'auuteur du projet
            $project->user->notify(new NewCommentReplyPosted($commentReply, $project, auth()->user()));
        }

        //je redirige l'utilisateur vers le projet publié
        return redirect()->route('projects.show', compact('project', 'slug'));
    }


    /**
     * Update the comment reply in database.
     *
     * @param  \Illuminate\Http\Requests\EditCommentReply  $request
     * @param int $project | id the project
     * @param string $slug | slug of the project
     * @param int $comment | id the comment
     * @return \Illuminate\Http\Response
     */
    public function updateReply(EditCommentReply $request, $project, $slug, $comment)
    {

        //je stocke le projet concerné en variable
        $project = Project::where('status_id', '=', 2)->findOrFail($project);
        //je stocke le slug du projet concerné
        $slug = $project->slug;

        /* Edit comment reply */
        $commentReply = Comment::findOrFail($comment);
        $commentReply->content = purifier($request->edit_comment_reply_content);
        $commentReply->save();

        //je redirige l'utilisateur vers le projet publié
        return redirect()->route('projects.show', compact('project', 'slug'));
    }

    /*********** Super Admin ***********/

    public function adminDeleteComment($admin, $project, $slug, $comment)
    {

        $comment = Comment::findOrFail($comment);

        $project = Project::findOrFail($project);

        $slug = $project->slug;

        $commentAuthor = $comment->user;

        if (!$comment->exists()) {
            return redirect()->route('admin.showProject', [
                'adminId' => auth()->user()->id,
                'project' => $project,
                'slug' => $slug,
            ])->with('error', "Le commentaire n'existe pas.");
        }

        $comment->delete();

        if (!$comment) {
            return redirect()->route('admin.showProject', [
                'adminId' => auth()->user()->id,
                'project' => $project,
                'slug' => $slug,
            ])->with('status', "Une erreur s'est produite lors de la suppression du commentaire.");
        }
        $commentAuthor->notify(new SendMailToAuthorConcerningCommentDeletion($comment, $project, $commentAuthor));

        return redirect()->route('admin.showProject', [
            'adminId' => auth()->user()->id,
            'project' => $project,
            'slug' => $slug,
        ])->with('status', 'Le commentaire a bien été supprimé.');
    }
}
