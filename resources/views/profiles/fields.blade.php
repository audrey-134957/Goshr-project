<div class="user-profile-edition__field user-profile-edition__field--{{$className}} field">
    <label class="user-profile-edition__label user-profile-edition__label--{{$className}} label">{{$labelName}}</label>
    <div class="control">
        <input class="user-profile-edition__input user-profile-edition__input--{{$className}} @error($fieldName) is-danger @enderror is-rounded input" type="{{$typeName}}" name="{{$fieldName}}" value="{{$fieldValue}}">
    </div>
    @error($fieldName)
    <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>