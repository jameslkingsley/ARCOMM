<div class="row">
    <div class="col-md-4 mission-overview-card">
        <h4>
            {{ $mission->fulltextGamemode() }} on {{ $mission->map->display_name }}
        </h4>

        <h6>
            {{ $mission->date() }} &mdash; {{ $mission->time() }}
        </h6>

        <p class="m-t-2">{{ $mission->summary }}</p>
    </div>

    <div class="col-md-4 mission-overview-card">
        <h4 class="text-xs-center">
            {{ $mission->weather() }}
        </h4>

        <span class="mission-weather-image" style="background-image: url('{{ $mission->weatherImage() }}')"></span>
    </div>

    <div class="col-md-4 mission-overview-card">
        <h4>
            Published {{ $mission->created_at->diffForHumans() }}
        </h4>

        <h6>
            ARCMF {{ $mission->version() }}
        </h6>

        <hr class="m-t-3">

        <p class="text-xs-center p-t-3">
            @if (!$mission->isNew())
                Last played {{ $mission->last_played->diffForHumans() }}
            @else
                Mission has not been played in an operation yet! Soon&trade;
            @endif
        </p>
    </div>
</div>
