<div class="auth-box__field field">
    <label class="auth-box__label label">{{$labelName}}</label>
    <div class="control">
        <input class="auth-box__input input @error($fieldName) is-danger @enderror is-rounded" type="{{$typeName}}" name="{{$fieldName}}" value="{{old($fieldName)}}">
    </div>
    @error($fieldName)
    <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>