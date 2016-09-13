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
        <div class="row">
            <div class="col-md-6 text-left">
                <h3 style="margin: 0;line-height: 38px;">Media</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ url('/media/create') }}" class="btn">Upload</a>
            </div>
        </div>

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