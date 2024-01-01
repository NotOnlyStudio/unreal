@extends("layouts.app")

@section("title","Gallery")

@php
    $meta_info = json_decode(\Illuminate\Support\Facades\File::get(resource_path()."/local-settings/meta.txt"));
@endphp

@section("description","UnrealShop site gallery")
@section("keywords",$meta_info->keywords)



@section("meta_data")

<meta property="og:title" content="Gallery" />
<meta property="og:url" content="{{url()->current()}}" />
<meta property="og:description" content="UnrealShop site gallery" />
<meta property="og:locale" content="en_US" />
<meta property="og:locale:alternate" content="en_EN" />
<meta property="og:site_name" content="{{config('app.name')}}" />
<meta property="og:type" content="webpage" />
<meta property="og:image" content="https://{{$_SERVER['HTTP_HOST']."/storage/app/public/gallery/user-".$gallery[0]->user->id."/".json_decode($gallery[0]->photos)[0]}}" />

@endsection


@section("content")
    @if(count($gallery) != 0)
        <gallery :server-data="{{json_encode($gallery)}}"></gallery>
    @else
    <div class="d-flex flex-column">
    <div class="container"><p class="breadcrumbs"><a href="/" style="color:#b3b3b3;text-decoration: none">UnrealShop</a> > Gallery</p></div>
    <div>
        <div class="d-flex ">
                <div class="container mt-4">
                    <p class="block-title tr-none">Gallery</p>
                    <div class="w-100 py-3 d-flex justify-content-start">
                        <a href="/upload-art" class="btn btn-bordered sm gallery-btn">+   Add Art</a>
                    </div>
                    <div class="alert alert-warning">
                        Gallery is empty
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
