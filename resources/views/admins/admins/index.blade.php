@extends('partials.admin-base-layout')

@section('admin-title', 'administrateurs')

@section('admin-header-subtitle', 'administrateurs')

@section('layout-content')


<div class="search-box">
    <form action="{{route('admin.search',
                    [
                        'adminId' => auth()->user()->id
                    ]
                    )}}" method="GET" class="search-box__form">

        <input type="search" class="search-box__search-input input is-rounded" name="q" placeholder="Rechercher...">
        <butonn class="search-box__button search-box__button--submit button is-rounded">
            <i class="fa fa-search" aria-hidden="true"></i>
        </butonn>
    </form>
</div>

<section class="section">
    <div class="users-list">
        <span class="users-list__users-number">{{$admins->count()}} {{$text}}</span>

        @foreach($admins as $adminUser)
        <div class="user-card card">
            <div class="user-card__card-header card-header">
                <figure class="user-card__image image is-48x48" style="margin: auto;">
                    <img class="is-rounded" src="{{$adminUser->getImage($adminUser)}}" alt="Placeholder image">
                </figure>
            </div>
            <div class="user-card__card-content card-content">

                @if($adminUser->name && $adminUser->firstname !== NULL)
                <div class="user-card__complete-name-box">
                    @php
                    $completeName = $adminUser->firstname. ' '.$adminUser->name;
                    @endphp
                    <span class="user-card__complete-name">{{$completeName}}</span>
                </div>
                @endif

                <div class="user-card__pseudo-box user-card__info">
                    <span class="user-card__title title" style="display: block;">pseudonyme</span>
                    <span class="section__user-info section__user-username">{{$adminUser->username}}</span>
                </div>

                <div class="user-card__email-box user-card__info">
                    <span class="user-card__title title" style="display: block;">email</span>
                    <span>{{$adminUser->email}}</span>
                </div>

                <div class="user-card__existance-box user-card__info">
                    <span class="user-card__title title" style="display: block;">ancienneté</span>
                    <span>membre depuis le 10 jan 2000</span>
                </div>

                <div class="user-card__status-box user-card__info">
                    <span class="user-card__title title" style="display: block;">status</span>
                    <span>{{$adminUser->level->name}}</span>
                </div>
            </div>
            <div class="user-card__card-footer card-footer">
                <a href="{{route('admin.editAdmin', [
                    'adminId' => auth()->user()->id,
                'adminUserFirstname' => $adminUser->firstname_slug,
                'adminUserName' => $adminUser->name_slug
                ])}}" class="user-card__button user-card__button--edit button is-rounded is-info is-outlined" style="padding:0;width:40px;height:40px;"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                <button class="user-card__button modal-button button is-rounded is-link is-outlined" type="button" style="padding:0;width:40px;height:40px;"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                @include('partials.modals.deletion.admins.modal')

                <button class="user-card__button modal-button button is-danger is-rounded is-outlined" type="button" style="padding:0;width:40px;height:40px;"><i class="fa fa-ban" aria-hidden="true"></i></button>
                @include('partials.modals.bans.admins.modal')

            </div>
        </div>

        @endforeach
    </div>
</section>


@endsection