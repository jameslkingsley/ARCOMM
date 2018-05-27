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
                    Average Turnout: {{
                        floor($operation->attendances->groupBy('mission_id')->map(function ($a) {
                            return $a->sum('present');
                        })->average())
                    }}
                    <span class="pull-right text-muted">
                        @php
                            $missionGrouped = $operation->attendances->where('present', true)->groupBy('mission_id')->all();
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
                    <table class="table w100">
                        @foreach ($operation->attendances->groupBy('user_id') as $user => $attendances)
                            <tr>
                                <td align="left">{{ $attendances[0]->user->username }}</td>
                                @foreach ($attendances as $attendance)
                                    <td width="150" align="right">
                                        @if ($attendance->present)
                                            <span class="text-success">{{ $attendance->mission ? $attendance->mission->display_name : 'PRESENT' }}</span>
                                        @else
                                            @if ($attendance->booked())
                                                <span class="text-muted">{{ $attendance->mission ? $attendance->mission->display_name : 'BOOKED' }}</span>
                                            @else
                                                <span class="text-danger">{{ $attendance->mission ? $attendance->mission->display_name : 'ABSENT' }}</span>
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        @endforeach
    </div>
@endsection
