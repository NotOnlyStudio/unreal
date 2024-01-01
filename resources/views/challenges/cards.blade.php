@extends("layouts.app")

@section("title","Challenge ".$challengeInfo->name)



@section("content")
    <div class="d-flex flex-column">
        <challenge-cards challenge-name="{{$challengeInfo->name}}" current-link="{{$currentChallenge != null ? $currentChallenge->alias : 0}}" alias="{{$alias}}" :server-data="{{json_encode($cards)}}" ></challenge-cards>
        <div class="container">
            {{$cards->links()}}
        </div>
    </div>
@endsection