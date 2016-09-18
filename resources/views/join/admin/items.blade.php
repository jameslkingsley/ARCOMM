<ul class="jr-list">
    @foreach ($joinRequests as $jr)
        <li>
            <a href="{{ action('JoinController@show', ['jr' => $jr]) }}" class="jr-item" data-id="{{ $jr->id }}">
                <h1>{{ $jr->name }}</h1>
                <p>
                    {{ $jr->created_at->diffForHumans() }}
                    <br />
                    {{ $jr->age }}, {{ $jr->location }}
                </p>
            </a>
        </li>
    @endforeach
</ul>