@extends("layouts.cabinet")

@section("title","Bookmarks blog")

@section("content")

<div class="d-flex flex-column">
    <bookmarks-nav></bookmarks-nav>
    <div class="container">
        <bookmarks-blog :server-data="{{json_encode($blogs)}}"></bookmarks-blog>
    </div>

</div>
@endsection