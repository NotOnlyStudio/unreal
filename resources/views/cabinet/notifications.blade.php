@extends("layouts.cabinet")

@section("title","Notifications")



@section("content")

<div class="container">
    <notifications :notifications="{{$notification}}"></notifications>
</div>

@endsection