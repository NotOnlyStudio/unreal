@extends("layouts.app")

@section("title",$info->title)

@section("content")
    <edit-article :info="{{json_encode($info)}}"></edit-article>
@endsection