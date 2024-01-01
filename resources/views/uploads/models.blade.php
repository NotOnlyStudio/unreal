@extends("layouts.app")


@section("title","Upload model")

@section("content")
    <upload-model :user-info="{{\App\Models\User::where('id','=',Auth::id())->select('id','name','photo','rang_id')->with('rang:id,name')->first()}}"></upload-model>
@endsection