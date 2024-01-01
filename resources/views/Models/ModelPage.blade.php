@extends("layouts.app")

@section("title",$info->title)

@php
    $meta = json_decode($info->meta);
    $meta = $meta ? $meta : (Object)[
        "keywords"=>"",
        "description"=>"",
    ];
    $photo = $_SERVER['HTTP_HOST']."/storage/app/public/models/user-".$info->user_id."/".json_decode($info->photos)[0];
    $url = url()->current();
@endphp

@section("keywords", $meta->keywords)
@section("description",$meta->description)

@section("meta")
{
    "@context":"http://schema.org",
    "@type":"3DModel",
    "name":"{{$info->title}}",
    "uploadDate":"{{$info->created_at}}",
    "image":"{{$photo}}",
    "description":"{{$meta->description != "" ? $meta->description : json_decode($info->props)->description}}",
    "url":"{{$url}}",
    "author":{
        "@context":"http://schema.org",
        "@type":"Person",
        "name":"{{$info->users->name}}",
        "url":"{{$_SERVER['HTTP_HOST']."/user/".$info->users->id}}"
    },
    "aggregateRating":
    {
        "@type":"AggregateRating",
        "@context":"http://schema.org",
        "ratingValue":{{$info->likes_count}},
        "ratingCount":{{$info->likes_count+$info->dislikes_count}}
    }
}
@endsection

@section("meta_data")

<link rel="icon" type="image/png" sizes="32x32" href="{{$photo}}">
<meta property="og:title" content="{{$info->title}}" />
<meta property="og:url" content="{{$url}}" />
<meta property="og:type" content="product" />
<meta property="og:determiner" content="the" />
<meta property="og:description" content="Доступна для скачивания, Платформа: {{json_decode($info->props)->version}}, {{json_decode($info->props)->bake? "bake": ""}} {{json_decode($info->props)->rtx? ",rtx": ""}}" />
<meta property="og:locale" content="en_US" />
<meta property="og:locale:alternate" content="en_EN" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:image" content="https://{{$photo}}" />
<meta property="og:image:width" content="10"/>
<meta property="og:image:height" content="10"/>
<meta property="og:site_name" content="{{config('app.name')}}" />
<meta property="og:image:alt" content="{{$info->title}}" />
<meta property="og:image" content="https://{{$photo}}" />

@endsection

@section("content")

<model :info="{{ $info }}"></model>

@endsection
