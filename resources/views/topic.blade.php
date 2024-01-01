@extends("layouts.app")

@section("title",$topic->title)

@php
    $meta = json_decode($topic->meta);
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
    "headline":"{{$topic->title}}",
    "datePublished":"{{$topic->created_at}}",
    "dateModified":"{{$topic->updated_at}}",
    "description":"{{$meta->description}}",
    "url":"{{$url}}",
    "mainEntityOfPage":"{{$_SERVER['HTTP_HOST']}}/blog",
    "author":{
        "@context":"http://schema.org",
        "@type":"Person",
        "name":"{{$topic->user->name}}",
        "url":"{{$_SERVER['HTTP_HOST']."/user/".$topic->user->id}}"
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

<meta property="og:title" content="{{$topic->title}}" />
<meta property="og:url" content="{{$url}}" />
<meta property="og:type" content="product" />
<meta property="og:determiner" content="the" />
<meta property="og:description" content="{{$meta->description}}" />
<meta property="og:locale" content="en_US" />
<meta property="og:locale:alternate" content="en_EN" />
<meta property="og:image:width" content="1200"/>
<meta property="og:image:height" content="630"/>
<meta property="og:site_name" content="{{config('app.name')}}" />
<meta property="og:image:alt" content="{{$topic->title}}" />
<meta property="og:image" content="https://{{$_SERVER['HTTP_HOST']}}/img/blog-image.jpg" />

@endsection


@section("content")
    <topic-page :server-data="{{json_encode($topic)}}"></topic-page>
@endsection
