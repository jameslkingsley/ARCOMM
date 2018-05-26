@extends('layout')

@section('title')
    Attendance
@endsection

@section('header-color')
    primary
@endsection

@section('content')
    <div class="container" id="users-content">
        <div style="display: inline-block; width: 100%; margin-bottom: 2rem">
            <form method="post" action="{{ url('/hub/attendance') }}">
                <button type="submit" class="btn btn-raised btn-primary pull-right">Collect Attendance</button>
            </form>
        </div>

        @foreach ($operations as $operation)
            @if ($operation->attendances->isNotEmpty())
                <h5 class="m-t-0 m-b-2" title="{{ $operation->starts_at }}">
                    {{ $operation->starts_at->format('jS F Y') }}
                    &middot;
                    Turnout: {{ $operation->actualTurnout() }}
                    <span class="pull-right text-muted">
                        @php
                            $missionGrouped = $operation->attendances->groupBy('mission_id')->all();
                        @endphp

                        @foreach ($operation->missionsResolved() as $index => $mission)
                            {{ $mission->display_name }} ({{
                                (array_key_exists($mission->id, $missionGrouped))
                                ? count($missionGrouped[$mission->id])
                                : '?'
                            }})@if ($index < 2), @endif
                        @endforeach
                    </span>
                </h5>

                <div class="card" style="margin-bottom: 5rem">
                    <div class="list-group">
                        @foreach ($operation->attendances->groupBy('user_id') as $user => $attendances)
                            <li class="list-group-item jr-item">
                                <span class="jr-item-title">
                                    <img src="{{ $attendances[0]->user->avatar }}" class="img-circle m-r-1" width="32">
                                    {{ $attendances[0]->user->username }}

                                    @foreach ($attendances->reverse() as $attendance)
                                        @if ($attendance->present)
                                            <span class="pull-right text-success m-l-2">{{ $attendance->mission ? $attendance->mission->display_name : 'PRESENT' }}</span>
                                        @else
                                            @if ($attendance->booked())
                                                <span class="pull-right text-muted m-l-2">{{ $attendance->mission ? $attendance->mission->display_name : 'BOOKED' }}</span>
                                            @else
                                                <span class="pull-right text-danger m-l-2">{{ $attendance->mission ? $attendance->mission->display_name : 'ABSENT' }}</span>
                                            @endif
                                        @endif
                                    @endforeach
                                </span>
                            </li>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection
