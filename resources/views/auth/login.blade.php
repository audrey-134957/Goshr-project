<!-- * Extension du layout parent * -->
@extends('partials.base-layout')


<!-- * Contenu * -->

@section('layout-content')

<div class="auth-box box">
    <span class="auth-box__title title">Connexion</span>

    <div class="auth-box__box-content">
        <form action="" method="POST">
            @csrf

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

            <button class="auth-box__button auth-box__button--is-submit button is-rounded" type="submit">connexion</button>
        </form>
        <hr>
        <div class="auth-box__box-footer">
            <small class="auth-box__related-text"><a href="{{route('forgotPwd.create')}}" class="auth-box__related-link">J'ai oublié mon mot de passe</a></small>
            <small class="auth-box__related-text">Pas encore de compte? <a href="{{route('register.create')}}" class="auth-box__related-link">Inscription</a></small>
        </div>
    </div>
</div>
@endsection