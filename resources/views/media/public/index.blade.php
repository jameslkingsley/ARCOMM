@extends('layout-public')

@section('title')
    Media
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ url('/css/magnific-popup.css') }}">
    <script type="text/javascript" src="{{ url('/js/jquery.magnific-popup.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(e) {
            $('#images').magnificPopup({
                delegate: '.thumbnail',
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        });
    </script>
@endsection

@section('content')
    <div class="content container">
        <h2>
            Media
            @if(App\User::isMember())
                <a href="{{ url('/media/create') }}" class="btn btn-primary btn-fill btn-dark pull-right">Upload</a>
            @endif
        </h2>

        <br />

        <div class="row" id="images">
            @foreach ($galleries as $gallery)
                @foreach ($gallery->getMedia() as $media)
                    <div class="col-lg-3 col-md-4 thumb">
                        <a href="{{ url($media->getUrl()) }}" class="thumbnail">
                            <img class="img-responsive" src="{{ url($media->getUrl('thumb')) }}" alt="{{ $media->name }}">
                        </a>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
@endsection