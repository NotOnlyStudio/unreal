@extends("layouts.app")

@section("title",$title)

@section("content")

<div class="w-100 d-flex flex-column">
    <div class="container ">
        <p class="breadcrumbs">
            <a href="/" style="color: rgb(179, 179, 179); text-decoration: none;">UnrealShop</a> 
            > {{$title}}</p>
    </div>    
    <div class="w-100 text-dark policy py-3" id="policy">
        {!! $content !!}
    </div>
</div>

@endsection