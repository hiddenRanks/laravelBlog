@extends('layout/master')

@section('content')
    <h1>{{ $data->title }}</h1>

    <div class="card">
        <div class="card-header">
            <span>{{ $data->user()->first()->writer }}</span>
            <span>{{ $data->created_at }}</span>
        </div>
        <div class="card-body">
            @foreach($image as $item)
                <img src="/image/{{ $item }}">
            @endforeach
            {{ $data->content }}
        </div>
    </div>
@endsection