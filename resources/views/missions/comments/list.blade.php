@foreach ($comments as $comment)
    @include('missions.comment', ['comment' => $comment])
@endforeach
