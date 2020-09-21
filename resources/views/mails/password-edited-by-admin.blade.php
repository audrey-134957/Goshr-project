@component('mail::message')

# {{$user->username}},

Le support est intervenu sur votre compte et a modifié votre mot de passe. 

Nous sommes ravis d'avoir pu vous venir en aide.

A très bientôt!<br>


{{config('app.name')}}

@endcomponent