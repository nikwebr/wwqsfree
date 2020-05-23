@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @section('content')
                        <table class="table table-bordered" id="users-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>From/To</th>
                                    <th>Date</th>
                                    <th>Size</th>
                                    <th>Files</th>
                                    <th>Expires</th>
                                    <th>IP</th>
                                    <th>Blocked</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    @stop

                    @push('scripts')
                    <script>
                    $(function() {
                        $('#users-table').DataTable({
                            processing: true,
                            serverSide: true,
                            pageLength: 100,
                            dom: 'Blfrtip',
                            select: true,
                            buttons: [
                                'selectAll',
                                'selectNone',
                                {
                                    text: 'Delet Selected',
                                    className: "btn btn-danger",
                                    action: function ( e, dt, node, config ) {
                                        var count = $('#users-table').DataTable().rows( { selected: true } ).count();
                                        if(count > 0){
                                            var askPerm = confirm('Are you sure you want to delete all selected items?', "Confirm Delete!");
                                            if (askPerm) {
                                                var allSelectedData = $('#users-table').DataTable().rows( { selected: true } ).data();
                                                var allSelectedCodes = [];
                                                allSelectedData.each(function( index ) {
                                                    allSelectedCodes.push(index.share_code);
                                                });

                                                $.ajax({
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                    },
                                                    url: '/delete/allselectedfiles',
                                                    type: 'POST',
                                                    data: JSON.stringify({codes : allSelectedCodes}),
                                                    contentType: 'application/json',
                                                    dataType: 'json',
                                                    processData: false,
                                                    success:function(response) {
                                                        alert('All Selected Items are deleted.');
                                                        location.reload();
                                                    }
                                                });

                                            }else{
                                                return false;
                                            }
                                        }

                                    }
                                }

                            ],
                            language: {
                                buttons: {
                                    selectAll: "Select all",
                                    selectNone: "Select none"
                                }
                            },
                            order: [[ 3, "desc" ]],
                            ajax: '{!! route('data') !!}',
                            columns: [
                                { data : 'id', name : 'id'},
                                { data: 'share_code', name: 'share_code' },
                                { data: function (data, type, row, meta) {
                                    var setHtml = '<b>From:</b><br>'+data.sender_email;
                                    setHtml = setHtml+'<br><b>To:</b><br>'+data.reciver_email;
                                    return setHtml;
                                }, name: 'from_to' },
                                { data: 'created_at', name: 'created_at' },
                                { data: 'totalSize', name: 'totalSize' },
                                { data: 'totalFiles', name: 'totalFiles' },
                                { data: 'validity', name: 'validity' },
                                { data: 'ip', name: 'ip' },
                                { data: function (data, type, row, meta) {
                                    var setHtml = '<b style="color:green;">No</b>';
                                    if(data.firewall == 'yes'){
                                        setHtml = '<b style="color:red;">Yes</b>';
                                    }
                                    return setHtml;
                                }, name: 'firewall' },
                                { data: function (data, type, row, meta) {
                                    var buttonGenDow = '<a href="'+data.downloadLink+'" title="Download Zip"><span class="glyphicon glyphicon-save"></span></a>';
                                    buttonGenDow = buttonGenDow+'&nbsp;&nbsp;|&nbsp;&nbsp;';
                                    buttonGenDow = buttonGenDow+'<a href="'+data.deleteLink+'" title="Delete" class="deleteShare" data-id="'+data.share_code+'"><span class="glyphicon glyphicon-trash"></span></a>';
                                    buttonGenDow = buttonGenDow+'&nbsp;&nbsp;|&nbsp;&nbsp;';

                                    if(data.firewall == 'yes'){
                                        buttonGenDow = buttonGenDow+'<a href="'+data.unblocklink+'" title="UnBlock IP" class="unblockShareIp" data-id="'+data.ip+'"><span class="glyphicon glyphicon-ok" style="color:green;"></span></a>';
                                    }else{
                                        buttonGenDow = buttonGenDow+'<a href="'+data.blocklink+'" title="Block IP" class="blockShareIp" data-id="'+data.ip+'"><span class="glyphicon glyphicon-ban-circle" style="color:red;"></span></a>';
                                    }

                                    return buttonGenDow;
                                }, name: 'downloadLink' }
                            ]
                        });

                        $(document).on('click', '.deleteShare', function(event) {
                            event.preventDefault();
                            var getShareID = $(this).attr('data-id');
                            var getDeleteUrl = $(this).attr('href');
                            var askPerm = confirm('Are you sure you want to delete '+getShareID+'?', "Confirm Delete!");
                            if (askPerm) {
                                window.location.href = getDeleteUrl;
                            }else{
                                return false;
                            }
                        });

                        $(document).on('click', '.blockShareIp', function(event) {
                            event.preventDefault();
                            var getShareID = $(this).attr('data-id');
                            var getDeleteUrl = $(this).attr('href');
                            var askPerm = confirm('Are you sure you want to block this IP : '+getShareID+'?', "Confirm IP Block!");
                            if (askPerm) {
                                window.location.href = getDeleteUrl;
                            }else{
                                return false;
                            }
                        });

                        $(document).on('click', '.unblockShareIp', function(event) {
                            event.preventDefault();
                            var getShareID = $(this).attr('data-id');
                            var getDeleteUrl = $(this).attr('href');
                            var askPerm = confirm('Are you sure you want to UNBLOCK this IP : '+getShareID+'?', "Confirm IP UNBLOCK!");
                            if (askPerm) {
                                window.location.href = getDeleteUrl;
                            }else{
                                return false;
                            }
                        });


                    });
                    </script>
                    @endpush
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
