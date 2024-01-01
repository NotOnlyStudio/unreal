@extends("layouts.app")

@section("title",$avard->user->name)

@section("content")
    <avard-page :server-data="{{json_encode($gallery)}}"></avard-page>
@endsection