<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Espace-administrateur @yield('title-admin-tab', ' - tableau de bord ')</title>
</head>

<body class="body body--admin">

    <x-admin-menu></x-admin-menu>


    <main class="main main--admin">
        @if (session('status'))
        <div class="notification notification--success is-success">
            <p class="notification__text">{{ session('status') }}</p>
        </div>

        @elseif(session('error'))
        <div class="notification notification--danger is-danger">
            <p class="notification__text">{{ session('error') }}</p>
        </div>
        @endif