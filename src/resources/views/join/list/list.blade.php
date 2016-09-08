@foreach (\App\JoinRequest::StatusList as $key => $value)
    <div id="{{ strtolower($key) }}">
        @include('join.list.items', ['status' => $value])
    </div>
@endforeach
