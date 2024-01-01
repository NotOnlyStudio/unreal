@extends("layouts.app")

@section("title","Confirm Email")

@section("content")
    
    <div style="height: 20vh" class="container-fluid">
        <b-alert show variant="warning">Email doesn't confirmed</b-alert>
    </div>
    <script>
        document.querySelector(".challenges").style.display = "none"
        @if(Auth::user()->verified == 1)
            window.location.href="/cabinet/notifications"
        @endif
    </script>
@endsection