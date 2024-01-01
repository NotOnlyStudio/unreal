@extends("layouts.app")

@section("title","FAQ")


@section("content")

<div class="container">
<faq-container :server-data="{{json_encode($faq)}}"></faq-container>
</div>


@endsection