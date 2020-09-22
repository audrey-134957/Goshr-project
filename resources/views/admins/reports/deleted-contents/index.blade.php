@extends('partials.admin-base-layout')

@section('admin-title', 'contenus supprimés')

@section('admin-header-subtitle', 'contenus supprimés')

@section('layout-content')
<div class="motives-list">
    <ul>
        @foreach($motives as $motive)
        <li><a href="{{route('admin.indexReports', ['adminId' => auth()->user()->id,'motive' => $motive->slug])}}" class="motives-list__link tag is-medium is-info is-light {{request()->motive == $motive->slug ? 'motives-list__link--is-active' : '' }} is-rounded">{{$motive->name}}</a></li>
        @endforeach
    </ul>
</div>
<form action="" method="GET" class="content-filtering-form">
    <!--tri par ordre récent / ancien :select-->
</form>

<div class="reports-list">
    @forelse($reports as $report)
    <div class="report-card card">
        <div class="report-card__header card-header" style="box-shadow:none;padding:1rem;display:flex;align-items: stretch;background-color: #ebfffc;
color: #00947e;">
            <i class="fa fa-check" aria-hidden="true" style="display:block;width:fit-content;margin-top:2px;margin-right:0.5rem;"></i>
            <small>signalement approuvé le {{$report->readDate()}}.</small>
        </div>
        <div class="report-card__content card-content">

            <div class="report-card__report-infos reports-infos">

                <figure class="project-card__image image is-40x40">
                    <img class="is-rounded" src="{{$report->reportAuthorAvatar()}}" alt="Placeholder image">
                </figure>

                {!! $report->content() !!}
            </div>

            <div class="report-card__motives-box">
                @foreach($report->motives as $motive)
                <span class="report-card__tag tag is-rounded is-info is-light">{{$motive->name}}</span>
                @endforeach
            </div>
        </div>
    </div>


    @empty
    <p>aucun contenu signalé</p>

    @endforelse
</div>

@endsection