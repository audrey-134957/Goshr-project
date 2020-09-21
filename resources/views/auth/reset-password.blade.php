<!-- * Extension du layout parent * -->
@extends('partials.base-layout')


<!-- * Contenu * -->

@section('layout-content')
<div class="auth-box box">
    <span class="auth-box__title title">Changement de votre mot de passe</span>

    <div class="auth-box__box-content">
        <form action="" method="POST">
            @method('PATCH')
            @csrf

            @include('partials.fields.auth-field', [
            'labelName' => 'Nouveau mot de passe',
            'typeName' => 'password',
            'fieldName' => 'password'
            ])

            @include('partials.fields.auth-field', [
            'labelName' => 'Confirmation du nouveau mot de passe',
            'typeName' => 'password',
            'fieldName' => 'password_confirmation'
            ])

            <button class="auth-box__button auth-box__button--is-submit button is-rounded" type="submit">Réinitialiser mon mot de passe</button>
        </form>
    </div>
</div>

@endsection