@extends("layouts.cabinet")

@section("title","Forum")

@php
    $meta_info = json_decode(\Illuminate\Support\Facades\File::get(resource_path()."/local-settings/meta.txt"));
@endphp
@section("description","Forum UnrealShop")
@section("keywords",$meta_info->keywords)

@section("meta_data")

<meta property="og:title" content="Forum" />
<meta property="og:url" content="{{url()->current()}}" />
<meta property="og:description" content="UnrealShop forum" />
<meta property="og:locale" content="en_US" />
<meta property="og:locale:alternate" content="en_EN" />
<meta property="og:site_name" content="{{config('app.name')}}" />
<meta property="og:type" content="webpage" />
<meta property="og:image" content="{{$_SERVER['HTTP_HOST']}}/img/blog-image.jpg" />

@endsection


@section("content")

<div class="container">
    <forum :topics="{{$topics}}"></forum>
</div>

@endsection