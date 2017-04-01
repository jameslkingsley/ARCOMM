@extends('layout')

@section('title')
    Applications
@endsection

@section('scripts')
    <script>
        $(document).ready(function(e) {
            window.jr = {
                status: "pending"
            };

            loadItems = function(status, order, callback) {
                $.ajax({
                    type: 'GET',
                    url: '{{ url('/hub/applications/api/items') }}/' + status + '/' + order,
                    success: function(data) {
                        if (typeof callback == "function")
                            callback(data);
                    }
                });
            }

            $('.status-filter').click(function(event) {
                var $this = $(this);
                var status = $(this).data("status");
                window.jr.status = status;

                $('.status-filter').removeClass("active");
                $this.addClass("active");
                setUrl('hub/applications/' + status);
                
                loadItems(status, "desc", function(data) {
                    $('#join-requests').html(data);
                    $('#search').val("");
                    $('.content').scrollTop(0);
                    $('#join-filter-form').show();
                });

                event.preventDefault();
            });

            $(document).on("click", ".jr-item", function(event) {
                var id = $(this).data("id");

                $.ajax({
                    type: 'GET',
                    url: '{{ url('/hub/applications/api/show') }}/' + id,
                    success: function(data) {
                        openPanel(data);
                    }
                });

                event.preventDefault();
            });

            $('#search').keyup(function(event) {
                var query = $(this).val().toLowerCase();

                $('.jr-item').each(function(i, e) {
                    var name = $(e).find('h1').html().toLowerCase();

                    if (name.includes(query)) {
                        $(e).parent().show();
                    } else {
                        $(e).parent().hide();
                    }
                });
            });

            $('#order').change(function(event) {
                var order = $(this).val();
                loadItems(window.jr.status, order, function(data) {
                    $('#join-requests').html(data);
                });
            });

            $.hubDropdown();
        });
    </script>
@endsection

@section('subnav')
    @foreach ($joinStatuses as $status)
        <a
            href="javascript:void(0)"
            data-status="{{ $status->permalink }}"
            class="status-filter {{ (Request::segment(3) == $status->permalink) ? 'active' : '' }}">
            {{ $status->text }}
        </a>
    @endforeach

    @if (auth()->user()->hasPermission('apps:emails'))
        <span class="separator"></span>

        <script>
            $(document).ready(function(e) {
                reloadEmails = function() {
                    $.ajax({
                        type: 'GET',
                        url: '{{ url('/hub/applications/api/emails') }}',
                        success: function(data) {
                            $('#join-requests').html(data);
                            $('#join-filter-form').hide();
                        }
                    });
                }

                $('#app-emails').click(function(event) {
                    reloadEmails();
                    event.preventDefault();
                });
            });
        </script>

        <a href="javascript:void(0)" id="app-emails">Emails</a>
    @endif
@endsection

@section('controls')
    <form method="post" id="join-filter-form">
        <input type="text" name="search" id="search" class="form-control" placeholder="Search" style="width:250px">
        <select name="order" class="form-control" id="order" style="width:150px">
            <option value="desc">Latest first</option>
            <option value="asc">Oldest first</option>
        </select>
    </form>
@endsection

@section('content')
    <div id="join-requests" class="pull-left full-width mt-5">
        @include('join.admin.items', ['joinRequests', $joinRequests])
    </div>
@endsection
