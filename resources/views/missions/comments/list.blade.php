@foreach ($comments as $comment)
    @include('missions.comments.item', ['comment' => $comment])
@endforeach
