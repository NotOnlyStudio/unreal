@extends("layouts.cabinet")

@section("title","Blog")



@section("content")

<div class="container">
    <blog :server-data="{{json_encode($blog)}}"></blog>
</div>

@endsection