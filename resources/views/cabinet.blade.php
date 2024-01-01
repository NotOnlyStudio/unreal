
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset("css/main.css")}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UShop</title>
</head>
<body>
<div class="container-fluid" id="app">
    <vheader site-name="UnrealShop" :user="{{App\Models\User::getCookie()}}" links="{{json_encode([['type'=>'button','href'=>'/upload-model','title'=>'Upload Model'],['type'=>'link','href'=>'/forum','title'=>'Forum'],['type'=>'link','href'=>'/blog','title'=>'Blog'],['type'=>'link','href'=>'/gallery','title'=>'Gallery'],['type'=>'link','href'=>'/store','title'=>'Store']])}}"></vheader>
    <banner site-name="UnrealShop" site-description="models for Unreal Engine" awards="award by Johny_CG"></banner>
    <app :user-data="{{App\Models\User::getCookie()}}"></app>
</div>
<footer>
    <ul class="list-unstyled">
        <li><a href="">Forum</a></li>
        <li><a href="">Blog</a></li>
        <li><a href="">Gallery</a></li>
        <li><a href="">Store</a></li>
        <li><a href="">Account</a></li>
        <li><a href="">Support</a></li>
        <li><a href="">FAQ</a></li>
    </ul>
    <span class="copyright">© 3DRabbit. All rights reserved</span>
</footer>
</div>
<script type="text/javascript" src="{{asset('js/cabinet.js')}}"></script>

</body>
</html>
