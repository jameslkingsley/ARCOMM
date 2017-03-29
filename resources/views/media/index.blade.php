@extends('layout-public')

@section('title')
    Media
@endsection

@section('scripts')
    <script>
        $(document).ready(function(e) {
            $('.media-gallery').magnificPopup({
                delegate: '.media-item-public',
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        });
    </script>

    @if (!auth()->guest() && auth()->user()->hasPermission('public_media:delete'))
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
                            caller.parents('.media-item-public').remove();
                        }
                    });

                    event.preventDefault();
                });
            });
        </script>
    @endif
@endsection

@section('content')
    <div class="content p0 container-fluid">
        @if (!auth()->guest() && auth()->user()->hasPermission('public_media:upload'))
            <div class="container text-center mb-5">
                <script>
                    $(document).ready(function(e) {
                        $('#btn-upload').dropzone({
                            url: '{{ url('/media') }}'
                        });
                    });
                </script>

                <a href="javascript:void(0)" class="btn btn-primary btn-fill btn-dark" id="btn-upload">Upload</a>
            </div>
        @endif

        <div class="media-gallery">
            @foreach ($galleries as $gallery)
                @foreach ($gallery->getMedia() as $media)
                    <a
                        href="{{ url($media->getUrl()) }}"
                        class="media-item-public"
                        style="background-image: url({{ url($media->getUrl('thumb')) }})">

                        @if (!auth()->guest() && auth()->user()->hasPermission('public_media:delete'))
                            <span
                                class="photo-delete"
                                data-gallery="{{ $gallery->id }}"
                                data-media="{{ $media->id }}"
                                title="Delete"
                            >&times;</span>
                        @endif
                    </a>
                @endforeach
            @endforeach
        </div>
    </div>
@endsection
