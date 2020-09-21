@extends('partials.admin-base-layout')

@section('admin-title', "signalement n° $report->id")

@section('admin-header-subtitle', "signalement n° $report->id")

@section('layout-content')
<div class="report-header">
    <div class="report-header__report-infos">

        <figure class="project-card__image image is-40x40">
            <img class="is-rounded" src="{{$report->reportAuthorAvatar()}}" alt="Placeholder image">
        </figure>

        {!! $report->content() !!}
    </div>

    <div style="margin-top:1rem;">
        @foreach($report->motives as $motive)
        <span class="tag is-rounded is-info is-light" style="margin:0.4rem;white-space:normal;height:fit-content;padding:0.2rem 1rem;">{{$motive->name}}</span>
        @endforeach
    </div>
</div>

<div class="comment-card" style="max-width: 60rem;border:solid 1px whitesmoke;background-color:white;margin-top:1in;">
    <div class="comment-card__content">
        <div class="media">
            <div class="media-left">
                <figure class="image is-32x32">
                    <img class="is-rounded" src="{{$comment->user->getImage($comment->user)}}" alt="Placeholder image">
                </figure>
            </div>
        </div>

        <div class="comment-card__right-part">
            <span class="comment-card__author-username">{{$comment->user->username}}</span>
            <time class="comment-card__publish-date">le {{$comment->publishDate()}}</time>

            <p class="comment-card__comment">{{$comment->content}}</p>
        </div>
    </div>
</div>

<form action="{{route('admin.storeAdminDecisionForCommentReport', [ 'adminName' => auth()->user()->name_slug,
            'adminFirstname' => auth()->user()->firstname_slug, 'report'=> $report,'comment' => $comment])}}" method="POST" style="display: flex;justify-content:flex-end;max-width:60rem;">
    @csrf
    @method('DELETE')
    <button class="button is-danger is-light is-rounded" type="submit" name="submit" value="approve">approuver</button>
    <button class="button is-rounded" type="submit" name="submit" value="disapprove" style="margin-left:1rem;">désapprouver</button>
</form>

@endsection