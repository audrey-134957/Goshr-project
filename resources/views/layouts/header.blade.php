<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Des projets écolos et économes</title>
</head>

<body class="body body--user">


    <x-navbar></x-navbar>

    @auth
   
    <x-menu></x-menu>
    @endauth

    @if(request()->route()->named('home.index') )
    <header class="header" role="img" aria-label="image montrant l'intérieur d'un appartement chaleureux.">
        <span class="header__title is-h2">Des idées de projets économes et écolos, ici.</span>
    </header>
    @endif



    <main class="main">
        {{-- @include('partials.breadcrumb') --}}

        @if(!request()->route()->named('contact.create'))
        @if(session('status'))
        <div class="notification notification--success is-success">
            <p class="notification__text">{{ session('status') }}</p>
        </div>

        @elseif(session('error'))
        <div class="notification notification--danger is-danger">
            <p class="notification__text">{{ session('error') }}</p>
        </div>
        @endif
        @endif