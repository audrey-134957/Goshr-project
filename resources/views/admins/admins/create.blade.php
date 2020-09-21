@extends('partials.admin-base-layout')

@section('admin-title', 'créer un administrateur')

@section('admin-header-subtitle', 'Créer un administrateur')

@section('layout-content')


<div class="user-profile-creation" style="overflow: auto;">
    <form action="{{route('admin.storeAdmin', ['adminId' => auth()->user()->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="user-profile-creation__fields fields">
            <div class="d-flex__left">
                <!-- * Champs email * -->
                <div class="user-profile-creation__field user-profile-creation__email-field field">
                    <label class="user-profile-creation__label user-profile-creation__label--email label">Email</label>
                    <div class="control">
                        <input class="user-profile-creation__input user-profile-creation__input--email @error('email') is-danger @enderror is-rounded input" type="email" name="email">
                    </div>
                    @error('email')
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- * Champs nom * -->
                <div class="user-profile-creation__field user-profile-creation__name-field field">
                    <label class="user-profile-creation__label user-profile-creation__label--name label">Nom</label>
                    <div class="control">
                        <input class="user-profile-creation__input user-profile-creation__input--name @error('name') is-danger @enderror is-rounded input" type="text" name="name">
                    </div>
                    @error('name')
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- * Champs prénom * -->
                <div class="user-profile-creation__field user-profile-creation__firstname-field field">
                    <label class="user-profile-creation__label user-profile-creation__label--firstname label">Prénom</label>
                    <div class="control">
                        <input class="user-profile-creation__input user-profile-creation__input--firstname @error('firstname') is-danger @enderror is-rounded input" type="text" name="firstname">
                    </div>
                    @error('firstname')
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="d-flex__right">
                <!-- * Champs nouveau mot de passe * -->
                <div class="user-profile-creation__field user-profile-creation__password-field user-profile-creation__password-field--new box__password--new field">
                    <label class="user-profile-creation__label user-profile-creation__label--password box__password--new label">Mot de passe</label>
                    <div class="control">
                        <input class="user-profile-creation__input user-profile-creation__input--password box__password--new @error('password') is-danger @enderror is-rounded input" type="password" name="password">
                    </div>
                    @error('password')
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- * Champs confirmation du nouveau mot de passe * -->
                <div class="user-profile-creation__field user-profile-creation__password-field user-profile-creation__password-field--new-confirmation box__password--new-confirmation field">
                    <label class="user-profile-creation__label user-profile-creation__label--password box__password--new-confirmation label">Confirmation du mot de passe</label>
                    <div class="control">
                        <input class="user-profile-creation__input user-profile-creation__input--password box__password--new-confirmation @error('password_confirmation') is-danger @enderror is-rounded input" type="password" name="password_confirmation">
                    </div>
                    @error('password_confirmation')
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="user-profile-creation__field user-profile-creation__role-select field">
                <div class="select @error ('admin_role') is-danger @enderror is-rounded">
                    <select name="admin_role">
                        @foreach($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                    </select>
                </div>
                @error('admin_role')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <button class="user-profile-creation__button user-profile-creation__button--update button is-rounded" type="submit">créer l'administrateur</button>
    </form>
</div>

@endsection