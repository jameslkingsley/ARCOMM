<ul class="jr-list">
    @foreach ($joinRequests as $jr)
        <li>
            <a href="/join/show/{{ $jr->id }}" class="jr-item">
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