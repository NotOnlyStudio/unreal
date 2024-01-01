@extends("layouts.app")

@section("title","Unreal Shop")

@php
    $meta_info = json_decode(\Illuminate\Support\Facades\File::get(resource_path()."/local-settings/meta.txt"));
@endphp

@section("description",$meta_info->description)
@section("keywords",$meta_info->keywords)

@section("meta_data")
    <meta property="og:description" content="{{$meta_info->description}}" />
    <meta property="og:image:width" content="1200"/>
    <meta property="og:image:height" content="630"/>
    <meta property="og:site_name" content="{{config('app.name')}}" />
    <meta property="og:image" content="https://{{$_SERVER['HTTP_HOST']}}/assets/logo-gray.png" />
    <meta property="og:image:alt" content="https://{{$_SERVER['HTTP_HOST']}}/assets/logo-gray.png" />
@endsection


@section('content')
    <app-index :products-data="{{json_encode($products)}}" :gallery-data="{{json_encode($gallery)}}" :blogs-data="{{json_encode($blog)}}"></app-index>
@endsection

