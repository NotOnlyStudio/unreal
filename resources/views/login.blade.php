@extends("layouts.app")


@section("title","Login")

@section("content")
    <div class="container">
        <login-form @if(Cookie::get("emailConfirm") !== null) confirmed="0" @else confirmed="1" @endif></login-form>
    </div>
@endsection