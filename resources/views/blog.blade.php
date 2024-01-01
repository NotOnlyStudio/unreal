
@extends("layouts.app")


@section("title","Blog")

@php
    $meta_info = json_decode(\Illuminate\Support\Facades\File::get(resource_path()."/local-settings/meta.txt"));
@endphp

@section("description","UnrealShop site news")
@section("keywords",$meta_info->keywords)


@section("meta_data")

<meta property="og:title" content="Unreal News" />
<meta property="og:url" content="{{url()->current()}}" />
<meta property="og:description" content="Blog in UnrealShop" />
<meta property="og:locale" content="en_US" />
<meta property="og:locale:alternate" content="en_EN" />
<meta property="og:site_name" content="{{config('app.name')}}" />
<meta property="og:type" content="webpage" />
<meta property="og:image" content="https://{{$_SERVER['HTTP_HOST']}}"/img/lamp.jpg" />

@endsection

@section("content")

<blog :server-data="{{json_encode($blog)}}"></blog>

@endsection
