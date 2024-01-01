@extends('layouts.app')

@section("title","Reset")
@section('content')
<form id="register_form">
    <h1 class="block-title tr-none">Reset password</h1>
    @csrf
    <reset-password email="{{$email}}" token="{{$token}}"></reset-password>
</form>

@endsection
