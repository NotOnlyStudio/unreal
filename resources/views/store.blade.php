@extends("layouts.app")


@section("title","Models")
@php
    $meta_info = json_decode(\Illuminate\Support\Facades\File::get(resource_path()."/local-settings/meta.txt"));
@endphp
@section("description",$meta_info->description)
@section("keywords",$meta_info->keywords)

@section("content")

<store route="{{url()->current()}}" :server-data="{{json_encode($products)}}"></store>

@endsection