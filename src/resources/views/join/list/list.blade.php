@extends('layout')

@section('title')
    Join Requests
@endsection

@section('scripts')
    <script>
        $(document).ready(function(e) {
            $('#tabs').tabs();
        });
    </script>
@endsection

@section('content')
    <div id="tabs">
        <ul>
            @foreach (\App\JoinRequest::StatusList as $key => $value)
                <li><a href="#{{ strtolower($key) }}">{{ $key }}</a></li>
            @endforeach
        </ul>
        @foreach (\App\JoinRequest::StatusList as $key => $value)
            <div id="{{ strtolower($key) }}">
                @include('join.list.items', ['status' => $value])
            </div>
        @endforeach
    </div>
@endsection