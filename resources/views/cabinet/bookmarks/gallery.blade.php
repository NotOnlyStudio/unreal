@extends("layouts.cabinet")

@section("title","Bookmarks gallery")

@section("content")
<div class="d-flex flex-column">
<bookmarks-nav></bookmarks-nav>

<div class="container">
    <bookmarks-gallery :server-data="{{json_encode($gallery)}}"></bookmarks-gallery>
</div>

</div>
@endsection