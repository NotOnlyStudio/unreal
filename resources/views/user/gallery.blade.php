@extends("layouts.user")

@section("title","Gallery")

@section("content")
    <div class="container">
        <gallery :server-data="{{json_encode([$moderation,$moderated])}}"></gallery>
    </div>
@endsection
