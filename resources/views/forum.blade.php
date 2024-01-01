@extends("layouts.app")

@section("title","Forum")


@section("content")

<forum :server-data="{{json_encode($forum)}}"></forum>

@endsection