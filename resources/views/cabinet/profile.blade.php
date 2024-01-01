<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="preload" href="{{asset("css/preloader.css")}}" as="style">
    <link rel="stylesheet" href="{{asset("css/preloader.css")}}" >
    <link rel="stylesheet" href="{{asset("css/main.css")}}">
    <title>{{$user->name}}</title>
</head>
<body>
<div class="container-fluid" id="app">
    <vheader site-name="UnrealShop" :user="{{Auth::user()}}" links="{{json_encode([['type'=>'button','href'=>'/upload-model','title'=>'Upload Model'],['type'=>'link','href'=>'/forum','title'=>'Forum'],['type'=>'link','href'=>'/blog','title'=>'Blog'],['type'=>'link','href'=>'/gallery','title'=>'Gallery'],['type'=>'link','href'=>'/store','title'=>'Store']])}}"/></vheader>
    @if (!Session::has("Preloader"))
        <div class="preloader__wrapper">
            <div class="preloader__inner">
                <div class="preloader-logo__wrapper">
                    <img src="image.png" class="preloader-logo" alt="">
                </div>
                <div class="logo-title-wrapper">
                    <p class="logo-title">UnrealShop<span>©</span></p>
                </div>
            </div>
        </div>
        @php
            $lifetime = time() + 60 * 60 * 24 * 365;
            Session::put('Preloader', $lifetime);
        @endphp
    @endif
    <banner
        site-name="UnrealShop"
        site-description="models for Unreal Engine"
    ></banner>
    <profile-header :user-info="{{$user}}"></profile-header>
</div>
<footer>
    <ul class="list-unstyled">
        <li><a href="/forum">Forum</a></li>
        <li><a href="/blog">Blog</a></li>
        <li><a href="/gallery">Gallery</a></li>
        <li><a href="/store">Store</a></li>
        <li><a href="/cabinet/notifications">Account</a></li>
        <li><a href="/support">Support</a></li>
        <li><a href="/faq">FAQ</a></li>
    </ul>
    <span class="copyright">© UnrealShop. All rights reserved</span>
</footer>
<script src="{{asset('js/cabinet.js')}}"></script>
</body>
</html>