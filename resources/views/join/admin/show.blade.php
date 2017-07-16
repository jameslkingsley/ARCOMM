@extends('layout')

@section('title')
    {{ $jr->name }} &mdash; Applications
@endsection

@section('header-color')
    primary
@endsection

@section('head')
@endsection

@section('subnav')
    @if (auth()->user()->hasPermission('apps:change_status'))
        @foreach ($joinStatuses as $status)
            @unless ($status->id == $jr->status->id)
                <li><a href="javascript:void(0)" onclick="javascript:setStatus({{ $status->id }})">{{ $status->text }}</a></li>
            @endunless
        @endforeach
        <a
            href="javascript:void(0)"
            data-status="{{ $status->permalink }}"
            class="subnav-link status-filter {{ (request()->segment(3) == $status->permalink) ? 'active' : '' }}">
            {{ $status->text }}
        </a>
    @endif
@endsection

@section('content')
    <div class="container">
        <div class="card p-a-3">
            <h2 class="jr-name">
                {{ $jr->name }}

                @if (auth()->user()->hasPermission('apps:emails'))
                    <script>
                        $(document).ready(function(e) {
                            $('#send-app-email').click(function(event) {
                                $('#app-emails').slideDown(150);
                                event.preventDefault();
                            });
                        });
                    </script>

                    <a
                        href="javascript:void(0)"
                        class="btn hub-btn jr-send-email pull-right jr-{{ strtolower($jr->status->permalink) }}-theme"
                        title="Choose an email to send"
                        id="send-app-email"
                    ><i class="fa fa-paper-plane"></i></a>
                @endif

                @if (auth()->user()->hasPermission('apps:change_status'))
                    <div id="status" class="pull-right">
                        @include('join.admin.status', [
                            'joinStatuses' => $joinStatuses,
                            'jr' => $jr
                        ])
                    </div>
                @endif
            </h2>

            @if (auth()->user()->hasPermission('apps:emails'))
                <div class="pull-left full-width" id="app-emails" style="display: none">
                    <ul>
                        @foreach ($emails as $email)
                            @unless ($email->id == 1)
                                <li>
                                    <a href="javascript:void(0)" class="{{ strtolower($jr->status->permalink) }}" data-id="{{ $email->id }}">
                                        {{ $email->subject }}
                                    </a>
                                </li>
                            @endunless
                        @endforeach
                    </ul>
                </div>
            @endif

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
        </div>
    </div>
@endsection


