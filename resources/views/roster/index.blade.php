@extends('layout-public')

@section('title')
    Roster
@endsection

@section('scripts')
    <script>
        $(document).ready(function(event) {});
    </script>
@endsection

@section('content')
    <div class="content container">
        <h2>Roster &mdash; {{ count($summaries) }} Members</h2>
        @foreach ($summaries as $member)
            <div class="roster-item">
                <div class="ri-avatar" style="background-image: url({{ $member->avatarMediumUrl }})"></div>
                <div class="ri-name">{{ $member->personaName }}</div>
            </div>
        @endforeach
    </div>
@endsection
