@extends('layout')

@section('title')
    Absences
@endsection

@section('header-color')
    primary
@endsection

@section('content')
    <div class="container" id="absence-content">
        @if ($operations->isEmpty())
            <p class="p-a-3 text-muted text-xs-center">No absence announcements posted yet.</p>
        @else
            @foreach ($operations as $operation)
                <h5 class="m-t-0 m-b-2" title="{{ $operation->starts_at }}">
                    {{ $operation->starts_at->format('jS F Y') }}
                    &middot;
                    Expected Turnout: {{ $operation->expectedTurnout() }}
                    <span class="pull-right text-muted">
                        {{ $operation->missionsResolved()->implode('display_name', ', ') }}
                    </span>
                </h5>

                <div class="card" style="margin-bottom: 5rem">
                    <div class="list-group">
                        @foreach ($operation->absences as $absence)
                            <li class="list-group-item jr-item">
                                <span class="jr-item-title">
                                    <img src="{{ $absence->user->avatar }}" class="img-circle m-r-1" width="32">
                                    {{ $absence->user->username }}
                                </span>

                                <br />

                                <span class="jr-item-meta" style="margin-left: 44px">
                                    {{ $absence->reason }}
                                </span>
                            </li>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
