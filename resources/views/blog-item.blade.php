@extends("layouts.app")

@section("title",$item->title)
@php
    $meta = json_decode($item->meta);
    $meta = $meta ? $meta : (Object)[
        "keywords"=>"",
        "description"=>"",
    ];
    $url = url()->current();
@endphp

@section("keywords", $meta->keywords)
@section("description",$meta->description)

@section("meta")
{
    "@context":"http://schema.org",
    "@type":"BlogPosting",
    "headline":"{{$item->title}}",
    "datePublished":"{{$item->created_at}}",
    "dateModified":"{{$item->updated_at}}",
    "description":"{{$meta->description}}",
    "url":"{{$url}}",
    "mainEntityOfPage":"{{$_SERVER['HTTP_HOST']}}/blog",
    "author":{
        "@context":"http://schema.org",
        "@type":"Person",
        "name":"{{$item->user->name}}",
        "url":"{{$_SERVER['HTTP_HOST']."/user/".$item->user->id}}"
    },
    "image":"{{$_SERVER['HTTP_HOST']}}/img/lamp.jpg",
    "publisher":{
        "@type":"Organization",
        "@context":"http://schema.org",
        "name":"UnrealShop",
        "description":"Marketplace",
        "logo":{
            "@type":"ImageObject",
            "@context":"http://schema.org",
            "url":"{{$_SERVER['HTTP_HOST']}}/assets/logo-gray.png",
            "contentUrl":"{{$_SERVER['HTTP_HOST']}}/assets/logo-gray.png"
        }
    }
}
@endsection

@section("meta_data")

<meta property="og:title" content="{{$item->title}}" />
<meta property="og:url" content="{{$url}}" />
<meta property="og:type" content="product" />
<meta property="og:determiner" content="the" />
<meta property="og:description" content="{{$meta->description}}" />
<meta property="og:locale" content="en_US" />
<meta property="og:locale:alternate" content="en_EN" />
<meta property="og:image:width" content="1200"/>
<meta property="og:image:height" content="630"/>
<meta property="og:site_name" content="{{config('app.name')}}" />
<meta property="og:image:alt" content="{{$item->title}}" />
<meta property="og:image" content="https://{{$_SERVER['HTTP_HOST']}}/img/blog-image.jpg" />

@endsection

@section("content")
    <blog-page :server-data="{{json_encode($item)}}"></blog-page>
@endsection
