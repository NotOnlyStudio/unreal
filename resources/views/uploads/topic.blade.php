@extends("layouts.app")


@section("title","Create topic")

@section("content")
    <topic-create :forum-info="{{json_encode($forum)}}" alias="{{$alias}}" :user-id="{{Auth::id()}}"></topic-create>
@endsection