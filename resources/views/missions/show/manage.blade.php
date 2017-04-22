@if ($mission->isMine() || auth()->user()->hasPermissions(['mission:update','mission:delete'], true))
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-cog"></i>
        </a>

        <div class="dropdown-menu">
            @if ($mission->isMine() || auth()->user()->hasPermission('mission:update'))
                <script>
                    $(document).ready(function(e) {
                        $('#update-mission').dropzone({
                            url: '{{ url('/hub/missions/' . $mission->id . '/update') }}',
                            acceptedFiles: '',
                            addedfile: function(file) {},
                            success: function(file, data) {
                                window.location = "{{ $mission->url() }}";
                            },
                            error: function(file, message) {
                                alert(message);
                            }
                        });
                    });
                </script>

                <a href="javascript:void(0)" class="dropdown-item" id="update-mission">Update</a>
            @endif

            @if ($mission->isMine() || auth()->user()->hasPermission('mission:delete'))
                <script>
                    $(document).ready(function(e) {
                        $('#delete-mission').click(function(event) {
                            event.preventDefault();
                            var canDelete = confirm("Are you sure you want to delete this mission?");
                            if (canDelete) window.location = $(this).attr('href');
                        });
                    });
                </script>
                
                <a href="javascript:void(0)" class="dropdown-item" id="delete-mission">Delete</a>
            @endif
        </div>
    </li>
@endif
{{-- 
<div class="mission-inner">
    <div class="mission-nav" style="{{ !$can_see_nav ? 'box-shadow:none' : '' }}">
        <span class="mission-version">
            @if ($mission->isNew())
                PUBLISHED {{ $mission->created_at->diffForHumans() }}
            @else
                LAST PLAYED {{ $mission->last_played->diffForHumans() }}
            @endif
            /
            ARCMF {{ $mission->version() }}
        </span>
    </div>
</div>
 --}}
