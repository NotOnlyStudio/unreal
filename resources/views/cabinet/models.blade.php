@extends("layouts.cabinet")

@section("title","Models")



@section("content")
<div class="container">
    <models-page :server-data="{{json_encode([$moderated,$moderation])}}"></models-page>
</div>

@endsection