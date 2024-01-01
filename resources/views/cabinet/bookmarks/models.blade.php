@extends("layouts.cabinet")

@section("title","Bookmarks models")

@section("content")
<div class="d-flex flex-column">
<bookmarks-nav></bookmarks-nav>

<div class="container">
    <bookmarks-models :server-data="{{json_encode($models)}}"></bookmarks-models>
</div>

</div>
@endsection