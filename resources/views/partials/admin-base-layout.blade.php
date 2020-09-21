<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Goshr | Espace administrateur - @yield('admin-title')</title>
</head>

<body class="admin-body">
    <!-- * Navbar * -->
    <x-admin-navbar></x-admin-navbar>

    <!-- * Header * -->

    @section('admin-header')
    <header class="admin-header">
        <h1 class="admin-header__title">Espace administrateur <span class="admin-header__title-breadcrumb">- @yield('admin-header-subtitle')</span></h1>
    </header>
    @show

    <!-- * Menu * -->

    @auth
    <x-admin-menu></x-admin-menu>
    @endauth


    <!-- * Main * -->

    <main class="admin-main">
        <x-flash-message></x-flash-message>

        <!-- * Contenu de la page * -->

        @yield('layout-content')

    </main>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>