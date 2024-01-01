@extends("layouts.app")


@section("title","Basket")
<div class="container">
    <p class="breadcrumbs">3d rabbit &gt; forum</p>
    <p class="block-title block-title__untransformed">Cart</p>
    <basket-wrapper :auth="{{Auth::check()}}"></basket-wrapper>


</div>

@section("content")

@endsection