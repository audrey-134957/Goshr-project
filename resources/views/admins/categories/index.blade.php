@extends('partials.admin-base-layout')

@section('admin-title', 'catégories')

@section('admin-header-subtitle', 'catégories')

@section('layout-content')
<div class="box" style="padding: 16px;margin:0.5rem;max-width:60rem;">
    <label class="label" style="font-weight: 500 !important;font-size:18px;">Créer une nouvelle catégorie</label>
    <form action="{{route('admin.storeCategory', ['adminId' => auth()->user()->id])}}" method="post" style="display:flex;">
        @csrf
        <div class="section__field field" style="max-width: 60rem;width:100%;">
            <div class="control">
                <input class="section__input input is-rounded" name="category_name" value="{{old('category_name')}}" style="max-width:60rem;width:100%;" type="text" placeholder="nom de la catégorie">
            </div>
        </div>
        <button class="button is-rounded" type="submit" style="margin-left: 0.5rem;">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </button>
    </form>
    @error('category_name')
    <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

@foreach($categories as $category)
<div class="card" style="padding:1rem;border-radius:5px;max-width:40rem;margin:1rem 0.5rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <span>{{$category->name}}</span>
        <div>
            <button class="btn-edit-cat button is-rounded" style="margin-right: 0.5rem;" type="button">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </button>
            <button class="modal-button button is-rounded" type="button">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </button>
            @include('admins.partials.modals.deletion.category.admin-modal')
        </div>
    </div>
    <form action="{{route('admin.updateCategory', ['adminId' => auth()->user()->id, 'category' => $category->slug])}}" method="post" class="form--edit is-hidden" style="display:flex;margin-top:1rem;">
        @csrf
        @method('PATCH')
        <div class="section__field field" style="max-width: 100% !important;width:100%;">
            <div class="control">
                <input class="section__input input is-rounded" type="text" name="edit_category_name" placeholder="nom de la catégorie" value="{{$category->name}}">
            </div>
        </div>
        <button class="button is-rounded" style="margin-left: 0.5rem;" type="submit">
            modifier
        </button>
    </form>
</div>
@endforeach
@endsection