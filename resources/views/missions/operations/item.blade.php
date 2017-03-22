<div class="operation-item" data-id="{{ $operation->id }}">
    @if ($operation->isNextToRun())
        <span class="operation-item-next-to-run"></span>
    @endif

    <span class="operation-item-date">
        {{ $operation->starts_at->format('jS F') }}
    </span>

    <span class="operation-item-time">
        {{ $operation->starts_at->format('H:i') }}
    </span>

    <div class="operation-item-missions">
        @for ($i = 1; $i <= 3; $i++)
            @php
                $item = \App\Models\Operations\OperationMission::where('play_order', $i)->where('operation_id', $operation->id)->first();
            @endphp

            @if (is_null($item))
                <a
                    href="javascript:void(0)"
                    class="operation-item-mission-item unassigned"
                    data-item="-1"
                    data-mission="-1"
                    data-order="{{ $i }}">
                    Assign Mission
                </a>
            @else
                <a
                    href="javascript:void(0)"
                    class="operation-item-mission-item assigned"
                    data-item="{{ $item->id }}"
                    data-mission="{{ $item->mission->id }}"
                    data-order="{{ $i }}">
                    <b>{{ $item->mission->display_name }}</b>
                </a>
            @endif
        @endfor
    </div>

    <div class="operation-item-controls">
        <a href="javascript:void(0)" class="btn hub-btn btn-primary pull-right ml-1 oc-delete" data-id="{{ $operation->id }}"><i class="fa fa-trash"></i></a>

        {{-- @if (!$operation->deployed)
            <a href="javascript:void(0)" class="btn hub-btn btn-primary pull-right oc-deploy" data-id="{{ $operation->id }}">Deploy</a>
        @else
            <a href="javascript:void(0)" class="btn hub-btn btn-primary pull-right oc-deployed">Deployed</a>
        @endif --}}
    </div>
</div>
