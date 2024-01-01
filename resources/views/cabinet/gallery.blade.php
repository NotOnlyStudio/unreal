@extends("layouts.cabinet")

@section("title","Gallery")



@section("content")

<div class="container">
    <gallery :server-data="{{json_encode([$moderation,$moderated])}}"></forum>
</div>

@endsection