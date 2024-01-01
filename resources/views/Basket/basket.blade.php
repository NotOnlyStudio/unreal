@extends("layouts.app")


@section("title","Basket")


@section("content")
    <div class="container">
        <p class="breadcrumbs">
            <a href="/" style="color: #b3b3b3; text-decoration: none">UnrealShop</a> >
            Basket
        </p>
        <p class="block-title block-title__untransformed">Cart</p>
        <basket-wrapper standart-price="{{\Config::get('app.payments.price')}}" :course="{{ $course }}" :auth="{{Auth::check() ? Auth::user() : 0 }}"></basket-wrapper>
    </div>

    <script type="text/javascript" src="https://js.bepaid.tech/widget/be_gateway.js"></script>
@endsection