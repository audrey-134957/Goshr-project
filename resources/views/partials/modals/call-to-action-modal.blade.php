<!-- * Extension du model * -->
@extends('partials.modals.modal-layout')

<!-- * Content * -->

@section('modal-content')
<div class="cta-content-box">
    <p><strong>Je veux faire partie de la communauté</strong></p>
    <a href="{{ route('register.create') }}" class="cta-content-box__link cta-content-box__link--is-submit button is-rounded">Je m'inscris !</a>

    <hr>
    <p><strong>Je possède déjà un compte</strong></p>
    <a href="{{ route('login.create') }}" class="cta-content-box__link cta-content-box__link--is-login button is-rounded">je me connecte!</a>
</div>
@endsection