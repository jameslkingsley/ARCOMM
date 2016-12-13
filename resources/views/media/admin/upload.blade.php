@extends('layout-public')

@section('title')
    Upload Media
@endsection

@section('head')
    <script type="text/javascript" src="{{ url('/js/dropzone.js') }}"></script>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(e) {});
    </script>
@endsection

@section('content')
    <div class="content container">
        <h2 class="text-center">Upload Media</h2>

        <br />

        <form method="post" action="{{ url('/media') }}" class="dropzone" enctype="multipart/form-data">
            <input type="file" name="image" multiple="true" style="display: none">
        </form>
    </div>
@endsection
