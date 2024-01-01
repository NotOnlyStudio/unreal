@extends("layouts.cabinet")


@section("title","Messanger")


@section("content")

@php
    $chat_ids = [];
    foreach ($users as $value)
    {
        $chat_ids[] = $value['id'];
    }
    foreach ($chat_ids as $value)
        {
            $count_notification[$value]['count_notification'] = \App\Models\Messange::where('chat_id', $value)
            ->where('message_from', '!=', auth()->user()->id)->where('view', 0)->get()->count();
        }
@endphp
<div class="container">
    @if(isset($count_notification))
        <messanger
            channels="{{$users}}"
            count-channels="{{$counts}}"
            count-notification="{{json_encode($count_notification)}}"
        ></messanger>
    @else
        <messanger
            channels="{{$users}}"
            count-channels="{{$counts}}"
        ></messanger>
    @endif
</div>

@endsection
