@extends('layout')

@section('title')
    Analytics
@endsection

@section('header-color')
    primary
@endsection

@section('content')
    <div class="container">
        <h5 class="m-t-0 m-b-2">Verified Rate</h5>

        <div class="card m-b-3 p-a-3">
            <table class="table">
                @php
                    $missions = App\Models\Missions\Mission::with('notes')
                        ->where('verified', true)
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $grouped = $missions->groupBy(function($mission) {
                        return $mission->created_at->format('W');
                    });
                @endphp

                @foreach ($grouped as $index => $group)
                    <tr><th>{{ $index }}</th></tr>

                    @foreach ($group as $mission)
                        @php
                            $noteCount = $mission->notes->count();
                        @endphp

                        <tr>
                            <td>{{ $mission->display_name }}</td>
                            <td>{{ $noteCount }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
        </div>
    </div>
@endsection
