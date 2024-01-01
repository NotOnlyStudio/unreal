@extends("layouts.app")

@section("title","Search results")


@section("content")

<forum-search :search-data="{{json_encode($searchData)}}"><forum-search/>

@endsection