@extends('layouts.header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
@endsection

@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">

            <h4 class="car-title d-flex justify-content-between align-items-center">
                Role Type List

                <button type="button"
                        class="btn btn-md btn-primary"
                        id="add_type"
                        data-toggle="modal"
                        data-target="#formType">
                    Add Type
                </button>
            </h4>

            <form method="GET" onsubmit="show()">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for=""><strong>Filter Department</strong></label>
                        <select name="filter_status" class="form-control form-control-sm js-example-basic-single">
                            <option disabled selected value>-Select Status-</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="">&nbsp;</label>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">
                                Filter 
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row">

                <div class="col-lg-6">
                    <span>Showing</span>

                    <form action="" method="get" class="d-inline-block">
                        <select name="entries"
                                class="form-control"
                                onchange="this.form.submit()">

                            <option value="10" @if($entries == 10) selected @endif>
                                10
                            </option>

                            <option value="25" @if($entries == 25) selected @endif>
                                25
                            </option>

                            <option value="50" @if($entries == 50) selected @endif>
                                50
                            </option>

                            <option value="100" @if($entries == 100) selected @endif>
                                100
                            </option>

                        </select>
                    </form>

                    <span>Entries</span>
                </div>

                <div class="col-lg-6">

                    <form method="GET"
                          class="custom_form mb-3"
                          enctype="multipart/form-data">

                        <div class="row height d-flex justify-content-end align-items-end">

                            <div class="col-md-8">

                                <div class="search">

                                    <i class="ti ti-search"></i>

                                    <input type="text"
                                           class="form-control"
                                           placeholder="Search Role"
                                           name="search"
                                           value="{{ $search }}">

                                    <button class="btn btn-sm btn-info">
                                        Search
                                    </button>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-striped table-bordered table-hover"
                       id="role_table"
                       width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Action</th>
                            <th width="35%">Type of Role</th>
                            <th width="50%">Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($typeRole->count() > 0)

                            @foreach ($typeRole as $type)
                                <tr>
                                    <td>
                                        @if($type->status == "Active")
                                            <button type="button"
                                                    name="edit"
                                                    class="edit btn btn-sm btn-warning editBtn"
                                                    data-toggle="modal"
                                                    data-target="#editRole-{{ $type->id }}"
                                                    data-id="{{ $type->id }}">

                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <button type="button"
                                                name="delete"
                                                class="delete btn btn-sm btn-danger deleteBtn"
                                                data-toggle="modal"
                                                data-target="#deleteRole-{{$type->id}}"
                                                data-id="{{ $type->id }}"
                                            >
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        @elseif($type->status == "Inactive")
                                            <div class="badge text-bg-primary text-wrap" style="width: 6rem;">
                                                N/A
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $type->roleType }}
                                    </td>

                                    <td>
                                        {{ $type->description }}
                                    </td>

                                    <td>
                                        @if($type->status == "Active")
                                            <div class="badge badge-success">{{$type->status}}</div>
                                        @elseif($type->status == "Inactive")
                                            <div class="badge badge-danger">{{$type->status}}</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else

                            <tr>
                                <td colspan="4" class="text-center">
                                    No matching records found
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @php
                $total = $typeRole->total();
                $currentPage = $typeRole->currentPage();
                $perPage = $typeRole->perPage();

                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp

            <p class="mt-3">
                Showing {{ $from }} to {{ $to }} of {{ $total }} entries
            </p>

        </div>
    </div>
</div>

@foreach ($typeRole as $type)
  @include('types_role.edit_type')
  @include('types_role.delete_type')
@endforeach

@include('types_role.add_type')
@endsection