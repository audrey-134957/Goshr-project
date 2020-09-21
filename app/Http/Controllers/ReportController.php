<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Motive;
use App\Models\Project;
use App\Models\Report;
use App\Models\Topic;
use App\Models\User;

use App\Notifications\SendMailToAuthorConcerningCommentDeletion;
use App\Notifications\SendMailToAuthorConcerningProjectDeletion;
use App\Notifications\SendMailToAuthorConcerningTopicDeletion;

use Carbon\Carbon;

use Illuminate\Http\Request;

class ReportController extends Controller
{


    /**
     * Show the listing of the reports
     */
    public function index()
    {
        if (request()->motive) {

            $reports = Report::with('user', 'reportable', 'motives')
                ->where('read_at', NULL)
                ->whereHas('motives', function ($query) {
                    //si le slug correpond à celui selectionné
                    $query->where('slug', request()->motive);
                    //je les récupère
                })
                ->orderBy('created_at', 'DESC')
                ->get();

            // je récupère les catégories
            $motives = Motive::all();
            //je récupère le nom de la catégorie qui correspond à celle sélectionnée.
            $motiveName = $motives->where('slug', request()->motive)->first()->name;
            //si aucune catégorie n'est sélectionnée
        } else {
            //je récupère toutes les catégories
            $motives = Motive::get();
            //le nom de catégorie sera null
            $motiveName = '';
            //je récupères tous les projets.

            $reports = Report::with('user', 'reportable', 'motives')
                ->where('read_at', NULL)
                ->orderBy('created_at', 'DESC')
                ->get();
        }


        return view('reports.admin-index', [
            'motives' => $motives,
            'motiveName' => $motiveName,
            'reports' => $reports
        ]);
    }


    /**
     * Store the project report
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $project | id of the project
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeProjectReport(Request $request, $project)
    {
        //je récupère le projet
        $project = Project::findOrFail($project);
        //tous les membres sauf l'auteur peuvent créer un report pour ce projet
        $this->authorize('doReport', $project);
        $motivesIds = $request->validate([
            'motives' => 'required|array|min:1',
            'motives.*' => 'required|integer|exists:motives,id'
        ]);

        /* Create new report */
        $report = new Report();
        $project->reports()->save($report);
        foreach ($motivesIds as $motiveId) {
            $report->motives()->attach($motiveId);
        }



        $admins = User::where('role_id', '!=', NULL)->get();



        //je redirige l'utilisateur avec un status de confirmation
        return redirect()->back()->with('status', 'Le projet a été signalé.');
    }

    /**
     * Store the topic report
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $topic | id of the topic
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeTopicReport(Request $request, $topic)
    {
        //je récupère le topic
        $topic = Topic::findOrFail($topic);
        //tous les membres sauf l'auteur peuvent créer un report pour ce topic
        $this->authorize('doReport', $topic);

        $motivesIds = $request->validate([
            'motives' => 'required|array|min:1',
            'motives.*' => 'required|integer|exists:motives,id'
        ]);

        /* Create new report */
        $report = new Report();
        $topic->reports()->save($report);
        foreach ($motivesIds as $motiveId) {
            $report->motives()->attach($motiveId);
        }

        //je redirige l'utilisateur avec un status de confirmation
        return redirect()->back()->with('status', 'Le topic a été signalé.');
    }

    /**
     * Store the topic reply report
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $topic | id of the topic
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeTopicReplyReport(Request $request, $topic)
    {

        //je retrouve le topic
        $topic = Topic::findOrFail($topic);
        //tous les membres sauf l'auteur peuvent créer un report pour ce topic
        $this->authorize('doReport', $topic);

        $motivesIds = $request->validate([
            'motives' => 'required|array|min:1',
            'motives.*' => 'required|integer|exists:motives,id'
        ]);

        /* Create new report */
        $report = new Report();
        $topic->reports()->save($report);
        foreach ($motivesIds as $motiveId) {
            $report->motives()->attach($motiveId);
        }

        //je redirige l'utilisateur avec un status de confirmation
        return redirect()->back()->with('status', 'Le topic a été signalé.');
    }

    /**
     * Store the comment report
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $comment | id of the comment
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeCommentReport(Request $request, $comment)
    {
        //je retrouve le commentaire
        $comment = Comment::findOrFail($comment);

        $motivesIds = $request->validate([
            'motives' => 'required|array|min:1',
            'motives.*' => 'required|integer|exists:motives,id'
        ]);

        /* Create new report */
        $report = new Report();
        $comment->reports()->save($report);
        foreach ($motivesIds as $motiveId) {
            $report->motives()->attach($motiveId);
        }

        //je redirige l'utilisateur avec un status de confirmation
        return redirect()->back()->with('status', 'Le commentaire a été signalé.');
    }

    /**
     * Store the comment reply report
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $comment | id of the comment reply
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeCommentReplyReport(Request $request, $comment)
    {
        //je retrouve le commentaire
        $comment = Comment::findOrFail($comment);

        $motivesIds = $request->validate([
            'motives' => 'required|array|min:1',
            'motives.*' => 'required|integer|exists:motives,id'
        ]);

        /* Create new report */
        $report = new Report();
        $comment->reports()->save($report);
        foreach ($motivesIds as $motiveId) {
            $report->motives()->attach($motiveId);
        }
        //je redirige l'utilisateur avec un status de confirmation
        return redirect()->back()->with('status', 'Le commentaire a été signalé.');
    }




    /*********** Super Admin ***********/

    public function showProjectReport($admin, $report, $project)
    {
        $admin = auth()->user();

        $report = Report::findOrFail($report);

        $project = Project::where('slug', $project)->firstOrFail();

        return view('reports.project.show', [
            'adminId' => auth()->user()->id,
            'report' => $report,
            'project' => $project
        ]);
    }

    public function showTopicReport($admin, $report, $topic)
    {
        $report = Report::findOrFail($report);

        $topic = Topic::findOrFail($topic);

        return view('reports.topic.show', [
            'adminId' => auth()->user()->id,
            'report' => $report,
            'topic' => $topic
        ]);
    }


    public function showCommentReport($admin, $report, $comment)
    {
        $report = Report::findOrFail($report);

        $comment = Comment::findOrFail($comment);

        return view('reports.comment.show', [
            'adminId' => auth()->user()->id,
            'report' => $report,
            'comment' => $comment
        ]);
    }


    public function storeAdminDecisionForProjectReport(Request $request, $admin, $report, $project)
    {

        $report = Report::findOrFail($report);
        $project = Project::findOrFail($project);

        if ($request->submit == 'disapprove') {

            $report->update(['read_at' => Carbon::now()]);

            return redirect()->route('admin.indexReports', [
                'adminId' => auth()->user()->id,
            ]);
        }

        $project->fictionnal_deletion =  1;
        $project->save();


        if (!$project->wasChanged()) {

            $report->update(['read_at' => Carbon::now()]);

            return redirect()->route('admin.showProjectReport', [
                'adminId' => auth()->user()->id,
                'report' => $report,
                'project' => $project
            ])->with('status', "Une erreur s'est produite lors de la suppression du projet.");
        }

        $report->update(['read_at' => Carbon::now()]);

        $anotherReports = $project->reports()->where('id', '!=', $report->id)->get();

        if ($anotherReports->count() > 0) {

            foreach ($anotherReports as $unreadReport) {
                $unreadReport->update(['read_at' => Carbon::now()]);
            }
        }

        $projectAuthor = $project->user;

        $project->user->notify(new SendMailToAuthorConcerningProjectDeletion($project, $projectAuthor));

        return  redirect()->route('admin.indexReports', [
            'adminId' => auth()->user()->id,
        ])->with('status', 'Le projet a été retiré.');
    }

    public function storeAdminDecisionForTopicReport(Request $request, $admin, $report, $topic)
    {

        $report = Report::where('id', $report)->firstOrFail();
        $topic = Topic::where('id', $topic)->firstOrFail();

        $project = $topic->topicable;

        dd($project);

        if ($request->submit == 'disapprove') {

            $report->update(['read_at' => Carbon::now()]);

            return redirect()->route('admin.indexReports', [
                'adminId' => auth()->user()->id,
            ]);
        }

        $topic->fictionnal_deletion =  1;
        $topic->save();


        if (!$topic->wasChanged()) {
            $report->update(['read_at' => Carbon::now()]);
            return redirect()->route('admin.showTopicReport', [
                'adminId' => auth()->user()->id,
                'report' => $report,
                'topic' => $topic

            ])->with('error', "Une erreur s'est produite lors de la suppression du topic .");
        }

        $report->update(['read_at' => Carbon::now()]);

        $anotherReports = $topic->reports()->where('id', '!=', $topic->id)->get();

        if ($anotherReports->count() > 0) {
            foreach ($anotherReports as $unreadReport) {
                $unreadReport->update(['read_at' => Carbon::now()]);
            }
        }

        $topicAuthor = $topic->user;

        $topic->user->notify(new SendMailToAuthorConcerningTopicDeletion($topic, $project, $topicAuthor));

        return  redirect()->route('admin.indexReports', [
            'adminId' => auth()->user()->id,
        ])->with('status', 'Le commentaire a été retiré.');
    }

    public function storeAdminDecisionForCommentReport(Request $request, $admin, $report, $comment)
    {

        $report = Report::findOrFail($report);
        $comment = Comment::findOrFail($comment);
        $project = $comment->commentable;



        if ($request->submit == 'disapprove') {
            // dd('disapprove');

            $report->update(['read_at' => Carbon::now()]);

            return redirect()->route('admin.indexReports', [
                'adminId' => auth()->user()->id,
            ]);
        }
        // dd('approve');

        $comment->fictionnal_deletion =  1;
        $comment->save();


        if (!$comment->wasChanged()) {

            $report->update(['read_at' => Carbon::now()]);

            return redirect()->route('admin.showCommentReport', [
                'adminId' => auth()->user()->id,
                'report' => $report,
                'comment' => $comment

            ])->with('status', "Une erreur s'est produite lors de la suppression du commentaire.");
        }

        $report->update(['read_at' => Carbon::now()]);

        $anotherReports = $comment->reports()->where('id', '!=', $comment->id)->get();

        if ($anotherReports->count() > 0) {

            foreach ($anotherReports as $unreadReport) {
                $unreadReport->update(['read_at' => Carbon::now()]);
            }
        }

        $commentAuthor = $comment->user;

        $comment->user->notify(new SendMailToAuthorConcerningCommentDeletion($comment, $project, $commentAuthor));

        return  redirect()->route('admin.indexReports', [
            'adminId' => auth()->user()->id,
        ])->with('status', 'Le commentaire a été retiré.');
    }
}
