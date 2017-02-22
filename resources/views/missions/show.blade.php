<div class="large-panel-content">
    <h1 class="mt-0 mb-0">
        {{ $mission->display_name }}
    </h1>

    <p class="text-muted">
        By {{ $mission->user()->username }}

        @if(!is_null($mission->last_played))
            &mdash;
            <span title="{{ $mission->last_played }}">Last played {{ $mission->last_played->diffForHumans() }}</span>
        @endif
    </p>

    <h3>Briefing</h3>
    <p>{!! $mission->summary !!}</p>

    <h3>Media</h3>

    <div class="mission-media">
        <a href="javascript:void(0)" class="mission-media-upload mission-media-item">
            <i class="fa fa-plus"></i>
        </a>
    </div>

    <div class="pull-left full-width" style="margin-bottom: 50px"></div>
</div>

<div class="large-panel-sidebar">
    <h2 class="mt-0">After-Action Report</h2>
</div>
