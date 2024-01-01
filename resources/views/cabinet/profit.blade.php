@extends("layouts.cabinet")
@section("title","Profit")
@section('errors')
    @php
        $user = Auth::user();
    @endphp
    @if($user->location == null)
        <div class="alert alert-warning w-100">
            To receive payments, you must specify the region of residence.
        </div>
    @endif
    @if($user->stripe_success != 1 || $user->stripe_account == null)
        <div class="alert alert-danger w-100">
            To receive payments, you need to go through an additional mini-registration.
            <br>
            @if($user->location == null)
                But first you must specify the region of residence.
            @else
                To do this, follow <a href="/stripe/register">this link</a>
            @endif
        </div>
    @endif
    {{-- @if($user->location != null && $user->stripe_account == null)
       <div class="alert alert-danger w-100">
          Please fill out the required information.
          <br>
          To do this, follow <a href="/stripe/register">this link</a>
       </div>
    @endif --}}
@endsection
@section("content")
    <div class="d-flex content-right">
        <div class="container d-flex flex-column">
            <div class="d-flex purchases">
                <purchases-block :models="{{json_encode($models)}}"></purchases-block>
                <charts :purchases-count="{{json_encode($purchasesCount)}}"></charts>
            </div>
            <p class="block-title mt-4">Withdrawal</p>
            <div class="d-flex withdrawal">

                <div class="transfers mx-0">
                    <div class="transfers__header border-standart">
                        <span>date</span>
                        {{--                        <span>transaction</span> --}}
                        <span>Status</span>
                        <span>Summary</span>
                        {{--                        <span>Invoice</span>--}}
                    </div>
                    <div class="transfers__content border-standart">
                        @foreach ($transactions as $tr)
                            <div>
                                @php
                                    //                                    $stripe = json_decode($tr->stripe_info);
                                @endphp

                                <span>{{date('m.d.Y',strtotime($tr->created_at))}}</span>
                                {{--                                <span>{{$stripe->transaction}}</span>--}}
                                <span>{{$tr->status == 1 ? "Payed" : "Error"}}</span>
                                <span>{{$tr->sum}} USD</span>
                                {{--                                <span>{{$stripe->invoice}}</span>--}}
                            </div>
                        @endforeach
                    </div>
                </div>
                @php
                    $money = 0;
                    if (isset($models[0]['bill'])) {
                        $money = json_encode($models[0]['bill']);
                    }
                @endphp
                <transfer-block :wallet-score="{{$money}}"></transfer-block>
            </div>
        </div>
    </div>
@endsection
