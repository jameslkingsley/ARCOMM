@if ($mission->briefingLocked($faction))
    <div class="alert alert-warning pull-left w-100 m-t-2" role="alert">
        <strong>This briefing is locked!</strong> Only the mission maker and testers can see it.
    </div>
@endif

<div class="pull-left w-100 m-t-3">
    @foreach ($mission->briefing($faction) as $subject)
        <h5>{{ $subject->title }}</h5>

        @foreach ($subject->paragraphs as $index => $paragraph)
            @if (starts_with($paragraph, '-'))
                <p style="{{
                        (($index + 1) != sizeof($subject->paragraphs)) ?
                        'margin: 0 !important;' :
                        'margin-bottom: 20px !important'
                    }}">
                    {!! $paragraph !!}
                </p>
            @else
                <p>{!! $paragraph !!}</p>
            @endif
        @endforeach
    @endforeach
</div>

@if (strtolower($faction) != 'civilian')
    <h5 class="m-b-0">Comm Plan &mdash; {{ $mission->acreOverview($faction) }}</h5>

    <span class="text-muted" style="color:#bd2c2c"><i>Comm plan does not indicate available units.</i></span><br />

    <span class="text-muted">Languages:</span> {{ $mission->acreLanguages($faction) }}<br />

    @if (!empty((array)$mission->config()->acre->{strtolower($faction)}->an_prc_343))
        <span class="text-muted">AN/PRC-343:</span> {{ $mission->acreRoles($faction, 'AN_PRC_343') }}<br />
    @endif

    @if (!empty((array)$mission->config()->acre->{strtolower($faction)}->an_prc_148))
        <span class="text-muted">AN/PRC-148:</span> {{ $mission->acreRoles($faction, 'AN_PRC_148') }}<br />
    @endif

    @if (!empty((array)$mission->config()->acre->{strtolower($faction)}->an_prc_152))
        <span class="text-muted">AN/PRC-152:</span> {{ $mission->acreRoles($faction, 'AN_PRC_152') }}<br />
    @endif

    @if (!empty((array)$mission->config()->acre->{strtolower($faction)}->an_prc_117f))
        <span class="text-muted">AN/PRC-117F:</span> {{ $mission->acreRoles($faction, 'AN_PRC_117F') }}<br />
    @endif

    @if (!empty((array)$mission->config()->acre->{strtolower($faction)}->an_prc_77))
        <span class="text-muted">AN/PRC-77:</span> {{ $mission->acreRoles($faction, 'AN_PRC_77') }}
    @endif
@endif
