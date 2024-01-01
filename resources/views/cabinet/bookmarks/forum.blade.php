@extends("layouts.cabinet")

@section("title","Bookmarks forums")

@section("content")
<div class="d-flex flex-column">
    
<bookmarks-nav></bookmarks-nav>
{{-- <bookmarks-forum :server-data="{{json_encode($forum)}}"></bookmarks-forum> --}}
<div class="container">
    <table class="w-100 table-pr" style="margin-top: 40px;">
        <thead><tr><th>Forum</th> <th>Topics</th> <th>Posts</th></tr></thead>
        <tbody>
            @foreach($sections as $section)
                @php
                    $inner_forums = array();
                    foreach($forums as $forum){
                        if($forum->forum_section_id == $section->id)
                            array_push($inner_forums,$forum);
                    }
                @endphp
                @if(count($inner_forums) != 0)
                    <tr class="t-title">
                        <td colspan="3" class="title"><img src="/storage/forums/icons/{{$section->icon}}" alt="section image">{{$section->title}}</td>
                    </tr>
                    @foreach($inner_forums as $sf)
                        <tr>
                            <td class="forum-title">
                                <div class="d-flex">
                                <bookmark-element item-id="{{$sf->id}}" item-type="App\Models\Forum" :checked="true"></bookmark-element>
                                    <a href="/forum/{{$sf->alias}}">{{$sf->title}}</a>
                                </div>
                            </td>
                            <td>{{$sf->topics_count}}</td>
                            <td>{{$sf->countPosts}}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
    <div class="pagination__container">
        {{$forums->links()}}
    </div>
</div>
</div>
@endsection