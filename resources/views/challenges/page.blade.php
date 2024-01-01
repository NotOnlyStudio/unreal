@extends("layouts.app")

@section("title",$challenge->name)

@section("content")
    <challenge-page :author-info="{{json_encode(Auth::user())}}" :challenge-info="{{json_encode($challenge)}}"></challenge-page>
@endsection