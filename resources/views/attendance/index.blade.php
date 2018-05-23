@extends('layout')

@section('title')
    Attendance
@endsection

@section('header-color')
    primary
@endsection

@section('content')
    <div class="container" id="users-content">
        @foreach ($operations as $operation)
            @if ($operation->attendances->isNotEmpty())
                <h5 class="m-t-0 m-b-2" title="{{ $operation->starts_at }}">
                    {{ $operation->starts_at->format('jS F Y') }}
                    &middot;
                    Turnout: {{ $operation->actualTurnout() }}
                    <span class="pull-right text-muted">
                        {{ $operation->missionsResolved()->implode('display_name', ', ') }}
                    </span>
                </h5>

                <div class="card" style="margin-bottom: 5rem">
                    <div class="list-group">
                        @foreach ($operation->attendances as $attendance)
                            <li class="list-group-item jr-item">
                                <span class="jr-item-title">
                                    <img src="{{ $attendance->user->avatar }}" class="img-circle m-r-1" width="32">
                                    {{ $attendance->user->username }}

                                    @if ($attendance->present)
                                        <span class="pull-right text-success">PRESENT</span>
                                    @else
                                        <span class="pull-right text-danger">ABSENT</span>
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection
