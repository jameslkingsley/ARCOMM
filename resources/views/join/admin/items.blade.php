<ul class="hub-list">
    @foreach ($joinRequests as $jr)
        <li>
            <a href="{{ url('/hub/applications/' . $jr->id) }}" class="hub-list-item jr-item" data-id="{{ $jr->id }}">
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
