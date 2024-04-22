@extends("layouts.app")


@section("title","Basket")


@section("content")

    <linker-basket-wrapper standart-price="{{\Config::get('app.payments.price')}}"
                           :auth="{{Auth::check() ? Auth::user() : 0 }}">

    </linker-basket-wrapper>
@endsection
