<!-- * Extension du layout parent * -->
@extends('partials.base-layout')


<!-- * Contenu * -->

@section('layout-content')

<div class="auth-box box">
    <span class="auth-box__title title">Inscription</span>

    <div class="auth-box__box-content">
        <form action="" method="POST">
            @csrf

            @include('partials.fields.auth-field', [
            'labelName' => 'Pseudo',
            'typeName' => 'text',
            'fieldName' => 'username'
            ])

            @include('partials.fields.auth-field', [
            'labelName' => 'Email',
            'typeName' => 'email',
            'fieldName' => 'email'
            ])

            @include('partials.fields.auth-field', [
            'labelName' => 'Mot de passe',
            'typeName' => 'password',
            'fieldName' => 'password'
            ])

            @include('partials.fields.auth-field', [
            'labelName' => 'Confirmation du mot de passe',
            'typeName' => 'password',
            'fieldName' => 'password_confirmation'
            ])

            <button class="auth-box__button auth-box__button--is-submit button is-rounded" type="submit">inscription</button>
        </form>
        <hr>
        <div class="auth-box__box-footer">
            <small>Déjà parmi nous? <a href="{{route('login.create')}}" class="auth-box__related-link">Connexion</a></small>
        </div>
    </div>
</div>
@endsection