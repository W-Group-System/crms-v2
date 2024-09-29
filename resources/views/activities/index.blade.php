@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Activity List
            <button type="button" class="btn btn-md btn-outline-primary" data-toggle="modal" data-target="#addActivity">New</button>
            @include('activities.new_activities')
            </h4>
            <div class="form-group">
                {{-- {{dd($status)}} --}}
                <form method="GET" >
                    <label>Show : </label>
                    <label class="checkbox-inline">
                        <input name="open" class="activity_status" type="checkbox" value="10" @if($open == 10) checked @endif> Open
                    </label>
                    <label class="checkbox-inline">
                        <input name="close" class="activity_status" type="checkbox" value="20" @if($close == 20) checked @endif> Closed
                    </label>
                    <button type="submit" class="btn btn-sm btn-primary">Filter Status</button>
                </form>
            </div>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-5">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Activity" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            @include('components.error')

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="activity_table" width="100%">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Activity Number</th>
                            <th>Schedule (Y-M-D)</th>
                            <th>Client</th>
                            <th>Title</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activities as $a)
                            <tr>
                                <td>
                                    <!-- <a href="{{url('view_activity/'.$a->id)}}" class="btn btn-info btn-sm" title="View Activity" target="_blank">
                                        <i class="ti-eye"></i>
                                    </a>  -->
                                    <button type="button" class="btn btn-outline-warning btn-sm edit_activity" title="Edit Activity" data-clientid="{{$a->ClientId}}" data-clientcontact="{{$a->ClientContactId}}" data-toggle="modal" data-target="#editActivity-{{$a->id}}">
                                        <i class="ti-pencil"></i>
                                    </button>
                                    <!-- <button type="button" class="btn btn-danger btn-sm delete_activity" title="Delete Activity" data-id="{{$a->id}}">
                                        <i class="ti-trash"></i>
                                    </button> -->
                                </td>
                                <td>
                                    <a href="{{url('view_activity/'.$a->id)}}">{{$a->ActivityNumber}}</a>
                                </td>
                                <td>{{$a->ScheduleFrom}}</td>
                                <td>
                                    <a href="{{url('view_client/'.$a->client->id)}}" target="_blank">
                                        {{$a->client->Name}}
                                    </a>
                                </td>
                                <td>{{$a->Title}}</td>
                                <td>
                                    @if($a->Status == 10)
                                        <div class="badge badge-success">Open</div>
                                    @else
                                        <div class="badge badge-danger">Closed</div>
                                    @endif
                                </td>
                            </tr>

                            @include('activities.edit_activities')
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $activities->appends(['search' => $search, 'open' => $open, 'close' => $close])->links() !!}
                @php
                    $total = $activities->total();
                    $currentPage = $activities->currentPage();
                    $perPage = $activities->perPage();
                    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp
            <p class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
        </div>
    </div>
</div>


<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function() {
        // $("input:checkbox").on('click', function() {
        //     var $box = $(this);

        //     if ($box.is(":checked")) {
        //         var group = "input:checkbox[name='" + $box.attr("name") + "']";

        //         $(group).prop("checked", false);
        //         $box.prop("checked", true);
        //     } else {
        //         $box.prop("checked", false);
        //     }
        // });

        // $('.activity_status').on('change', function() {
        //     $("#checkboxForm").submit();
        // })

        $('.edit_activity').on('click', function() {
            var clientId = $(this).data('clientid');
            var clientContact = $(this).data('clientcontact');

            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "{{url('edit_client_contact')}}",
                    data: {
                        clientId: clientId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res)
                    {
                        setTimeout(function() {
                            $('.ClientContactId').html(res)
                            $('.ClientContactId').val(clientContact);
                        }, 500)
                    }
                })
            }, 500)
        })
        
        $(".ClientId").on('change', function() {
            
            var client_id = $(this).val();

            $.ajax({
                type: "POST",
                url: "{{url('refresh_client_contact')}}",
                data: {
                    client_id: client_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res)
                {
                    $('.ClientContactId').html(res)
                }
            })
        })

        $('.delete_activity').on('click', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{url('delete_activity')}}",
                        data: {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            Swal.fire({
                                title: res.message,
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        }
                    })
                }
            });
        })
    })
</script>
@endsection