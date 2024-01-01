<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/admin/admin.css')}}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Admin</title>
</head>
<body>
<div id="app" class="{{!isset($_COOKIE['theme_mode']) || $_COOKIE['theme_mode'] == 'false' ? 'theme-dark' : 'theme-light'}}">
    <!-- <admin-header></admin-header> -->
    <!-- <index></index> -->
    @php

        $menu = isset($_COOKIE['right-menu']) && $_COOKIE['right-menu'] == "true" ? true : false;
        $menu_list = file_get_contents(resource_path()."/json/admin-menu.json");

    @endphp
    <div class="container-fluid d-flex flex-column">
        <header class="header">
            <div id="menu__icon"  class="{{$menu ? 'active' : ''}}">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="d-flex align-item-center">
            <mode-toggler mode-value="{{!isset($_COOKIE['theme_mode']) || $_COOKIE['theme_mode'] == 'false' ? false : true}}"></mode-toggler>
                <div class="user-menu">
                    @php
                        $user = Auth::user();
                    @endphp
                    <img src="{{$user->photo != null ? '/storage/app/public/avatars/'.$user->photo : '/img/user.png'}}" alt="">
                    <ul class="submenu">
                        <li><a href="/cabinet"><b-icon-person></b-icon-person><span>My profile</span></a></li>
                        <li><a href="/logout"><b-icon-door-open></b-icon-door-open><span class="ml-2">Logout</span></a></li>
                    </ul>
                </div>
            </div>
        </header>
        <div class="d-flex w-100">
            <div class="menu-right {{$menu ? 'active' : 'hiddenMenu'}}">
                <left-menu :menu="{{$menu_list}}" user-role="{{Auth::user()->permisions}}"></left-menu>
            </div>
            <div class="admin-content text-color" style="transition: .3s linear">
                @php
                    $url = "/".request()->path();
                @endphp
                <h1 class="mb-4">
                    @php
                        $menu_list = json_decode($menu_list)->menu;
                        $index = array_search($url, array_column($menu_list, "link"));
                        echo  $menu_list[$index]->title;
                    @endphp
                </h1>
                <router-view></router-view>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{asset("js/admin.js", false)}}"></script>
<script type="text/javascript">
    document.querySelector("#menu__icon").addEventListener("click", event => {
        document.querySelector("#menu__icon").classList.toggle("active")
        document.querySelector(".menu-right").classList.toggle("hiddenMenu")
        document.querySelector(".menu-right").classList.toggle("active")
        $cookies.set("right-menu", document.querySelector("#menu__icon").classList.contains("active") ? true : false)
    });

    document.querySelector(".user-menu").addEventListener("click", event => {
        document.querySelector(".user-menu").classList.toggle("active")
    })
    @if(!isset($_COOKIE['ROLE']))
        $cookies.set("ROLE", '{{Auth::user()->permisions}}',60 * 30 * 1);
    @endif
</script>
</body>
</html>
