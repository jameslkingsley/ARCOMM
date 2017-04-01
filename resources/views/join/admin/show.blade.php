<h1 class="jr-name">
    {{ $jr->name }}

    @if (auth()->user()->hasPermission('apps:emails'))
        <a
            href="javascript:void(0)"
            class="btn hub-btn jr-send-email pull-right jr-{{ strtolower($jr->status->permalink) }}-theme"
            title="Choose an email to send"
        ><i class="fa fa-paper-plane"></i></a>

        {{-- <div class="hub-dropdown hub-dropdown-right hub-dropdown-fixed-color jr-status-dropdown pull-right ml-3 {{ strtolower($jr->status->permalink) }}">
            <a href="javascript:void(0)">
                Email <i class="fa fa-angle-down"></i>
            </a>

            <ul>
                @foreach ($emails as $email)
                    <li>
                        <a href="javascript:void(0)" data-id="{{ $email->id }}">
                            {{ $email->subject }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div> --}}
    @endif

    @if (auth()->user()->hasPermission('apps:change_status'))
        <div id="status" class="pull-right">
            @include('join.admin.status', [
                'joinStatuses' => $joinStatuses,
                'jr' => $jr
            ])
        </div>
    @endif
</h1>

<div class="row">
    <div class="col-md-6">
        <i class="jr-icon fa fa-envelope"></i>
        <span class="jr-attr">{{ $jr->email }}</span>
    </div>

    <div class="col-md-6 {{ ($jr->age < env('JR_MIN_AGE')) ? 'error' : 'success' }}">
        <i class="jr-icon fa fa-calendar"></i>
        <span class="jr-attr">{{ $jr->age }}</span>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <i class="jr-icon fa fa-map-marker"></i>
        <span class="jr-attr">{{ $jr->location }}</span>
    </div>

    <div class="col-md-6">
        <i class="jr-icon fa fa-steam-square"></i>
        <span class="jr-attr"><a href="{{ $jr->steam }}" target="_newtab" class="jr-link">Steam Account</a></span>
    </div>
</div>

<div class="row">
    <div class="col-md-6 {{ ($jr->available) ? 'success' : 'error' }}">
        <i class="jr-icon fa fa-clock-o"></i>
        <span class="jr-attr">{{ ($jr->available) ? 'Available ' : 'Unavailable ' }} {{ env('SITE_OP_DAY') }}</span>
    </div>

    <div class="col-md-6 {{ ($jr->groups) ? 'error' : 'success' }}">
        <i class="jr-icon fa fa-users"></i>
        <span class="jr-attr">{{ ($jr->groups) ? 'Other groups' : 'No other groups' }}</span>
    </div>
</div>

<div class="row">
    <div class="col-md-6 {{ ($jr->apex) ? 'success' : 'error' }}">
        <i class="jr-icon fa fa-gift"></i>
        <span class="jr-attr">{{ ($jr->apex) ? 'Owns Apex' : 'Does not own Apex' }}</span>
    </div>

    <div class="col-md-6">
        <i class="jr-icon fa fa-globe"></i>
        <span class="jr-attr">
            {{ $jr->source->name }}
            @if (strlen($jr->source_text))
                ({{ $jr->source_text }})
            @endif
        </span>
    </div>
</div>

<h3 class="jr-subheading">Arma Experience</h3>
<p class="jr-content">{!! $jr->experience !!}</p>

<h3 class="jr-subheading">About</h3>
<p class="jr-content">{!! $jr->bio !!}</p>
