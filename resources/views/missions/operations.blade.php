<script>
    $(document).ready(function(e) {
        var slot = null;

        $(document).on('click', '.operation-item-mission-item', function(event) {
            var caller = $(this);
            var isAssigned = caller.hasClass('assigned');

            if (isAssigned) {
                $.ajax({
                    type: 'POST',
                    url: '{{ url('/hub/missions/operations/remove-mission') }}',
                    data: {'id': caller.data('item') || -1},
                    success: function(data) {
                        caller.html('Assign Mission');
                        caller.removeClass('assigned');
                        caller.addClass('unassigned');
                    }
                });

                return;
            }

            $('.operation-item-mission-item.unassigned').html('Assign Mission');

            caller.html('Pick a mission below');
            slot = caller;

            $('.operations-mission-browser').removeClass('hide');

            event.preventDefault();
        });

        $('.mission-item').click(function(event) {
            event.preventDefault();

            var caller = $(this);
            var mission_id = caller.data('id');
            var operation_id = slot.parents('.operation-item').data('id');
            var order = slot.data('order');

            if (slot != null) {
                $.ajax({
                    type: 'POST',
                    url: '{{ url('/hub/missions/operations/add-mission') }}',
                    data: {
                        'mission_id': mission_id,
                        'operation_id': operation_id,
                        'play_order': order
                    },
                    success: function(data) {
                        slot.data('mission', mission_id);
                        slot.data('item', data.trim());
                        slot.html('<b>' + caller.find('.mission-item-title').html() + '</b>');
                        slot.removeClass('unassigned');
                        slot.addClass('assigned');
                        $('.operations-mission-browser').addClass('hide');
                    }
                });
            }
        });

        $('#create-operation-form').submit(function(event) {
            $.ajax({
                type: 'POST',
                url: '{{ url('/hub/missions/operations/create-operation') }}',
                data: $('#create-operation-form').serialize(),
                success: function(data) {
                    $('.operations').prepend(data);
                }
            });

            event.preventDefault();
        });

        $(document).on('click', '.oc-delete', function(event) {
            var caller = $(this);

            $.ajax({
                type: 'POST',
                url: '{{ url('/hub/missions/operations/delete-operation') }}',
                data: {operation_id: caller.data('id')},
                success: function(data) {
                    caller.parents('.operation-item').remove();
                }
            });

            event.preventDefault();
        });
    });
</script>

@php
    use App\Models\Operations\Operation;
    use App\Models\Operations\OperationMission;
    use App\Models\Missions\Mission;
@endphp

<div class="large-panel-content full-page">
    <h1 class="mt-0 mb-5">
        Operations

        <form id="create-operation-form" class="pull-right">
            <input type="submit" value="Create Operation" class="btn hub-btn btn-primary pull-right ml-1">
            <input type="datetime-local" name="starts_at" id="create-operation-starts-at">
        </form>
    </h1>

    <div class="operations">
        @foreach (Operation::orderBy('starts_at', 'desc')->get() as $operation)
            @include('missions.operations.item', ['operation' => $operation])
        @endforeach
    </div>
</div>

<div class="operations-mission-browser hide">
    <h2 class="mission-section-heading" style="margin-top: 0 !important">New Missions</h2>

    <ul class="mission-group">
        @foreach (Mission::allNew() as $mission)
            @include('missions.item', ['mission' => $mission])
        @endforeach
    </ul>

    <h2 class="mission-section-heading">Past Missions</h2>

    <ul class="mission-group">
        @foreach (Mission::allPast() as $mission)
            @include('missions.item', ['mission' => $mission])
        @endforeach
    </ul>
</div>
