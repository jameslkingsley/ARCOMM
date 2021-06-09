@if ($mission->briefingLocked($faction))
    <div class="alert alert-warning pull-left w-100 m-t-2" role="alert">
        <strong>This briefing is locked!</strong> Only the mission maker and testers can see it.
    </div>
@endif

<div class="pull-left w-100 m-t-3">
    @foreach ($mission->briefing($faction) as $subject)
        <h5>{{ $subject->title }}</h5>       

        {!! $subject->paragraphs !!}
        <br/><br/>
    @endforeach
</div>
