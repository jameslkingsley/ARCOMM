@extends('layout-public')

@section('title')
    Media
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ url('/css/magnific-popup.css') }}">
    <script type="text/javascript" src="{{ url('/js/jquery.magnific-popup.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/js/dropzone.js') }}"></script>
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

    @if(auth()->user()->isAdmin())
        <script>
            $(document).ready(function(e) {
                $('.photo-delete').click(function(event) {
                    var caller = $(this);
                    var gallery_id = caller.data('gallery');
                    var media_id = caller.data('media');

                    $.ajax({
                        type: 'POST',
                        url: '{{ url('/media/delete') }}',
                        data: {
                            'gallery_id': gallery_id,
                            'media_id': media_id
                        },
                        success: function(data) {
                            caller.parents('.thumb').remove();
                        }
                    });

                    event.preventDefault();
                });
            });
        </script>
    @endif
@endsection

@section('content')
    <div class="content container">
        <h2>
            Media
            @if(auth()->user()->isAdmin())
                <script>
                    $(document).ready(function(e) {
                        $('#btn-upload').dropzone({
                            url: '{{ url('/media') }}'
                        });
                    });
                </script>

                <a href="javascript:void(0)" class="btn btn-primary btn-fill btn-dark pull-right" id="btn-upload">Upload</a>
            @endif
        </h2>

        <br />

        <div class="row" id="images">
            @foreach ($galleries as $gallery)
                @foreach ($gallery->getMedia() as $media)
                    <div class="col-lg-3 col-md-4 thumb">
                        @if(auth()->user()->isAdmin())
                            <a
                                href="javascript:void(0)"
                                class="photo-delete"
                                data-gallery="{{ $gallery->id }}"
                                data-media="{{ $media->id }}"
                                title="Delete"
                            >&times;</a>
                        @endif

                        <a href="{{ url($media->getUrl()) }}" class="thumbnail">
                            <img class="img-responsive" src="{{ url($media->getUrl('thumb')) }}" alt="{{ $media->name }}">
                        </a>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
@endsection
