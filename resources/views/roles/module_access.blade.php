@extends('layouts.header')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6"> 
                    <h3><strong>Department :</strong> {{$role->department->name}}</h3>
                    <h4><strong>Role :</strong> {{$role->name}}</h4>
                </div>
                <div class="col-lg-6" align="right">
                    <a href="{{ url('/role') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>
                </div>
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>Create</th>
                                    <th>Edit</th>
                                    <th>Update</th>
                                    <th>View</th>
                                    <th>Delete</th>
                                    <th>Approve</th>
                                </tr>
                            </thead>
                            <form method="POST" action="{{url('add_module_access')}}">
                                {{csrf_field()}}
                                <input type="hidden" name="department" value="{{$role->department->id}}">
                                <input type="hidden" name="role" value="{{$role->id}}">
                                <tbody>
                                    @foreach ($modules as $module)
                                        <input type="hidden" name="module[]" value="{{$module}}">
                                        @if(count($role->access) > 0)
                                            @foreach ($role->access as $access)
                                                {{-- @php
                                                    $currentAccess = $role->access->firstWhere('module_name', $module);
                                                @endphp --}}
                                                @if($module == $access->module_name)
                                                    <tr>
                                                        <td>{{$module}}</td>
                                                        <td>
                                                            <input type="checkbox" name="{{$module}}[create]" class="form-control" @if($access->create == "on") checked @endif>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="{{$module}}[edit]" class="form-control" @if($access->edit == "on") checked @endif>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="{{$module}}[update]" class="form-control" @if($access->update == "on") checked @endif>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="{{$module}}[view]" class="form-control" @if($access->view == "on") checked @endif>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="{{$module}}[delete]" class="form-control" @if($access->delete == "on") checked @endif>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="{{$module}}[approve]" class="form-control" @if($access->approve == "on") checked @endif>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>{{$module}}</td>
                                                <td>
                                                    <input type="checkbox" name="{{$module}}[create]" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="{{$module}}[edit]" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="{{$module}}[update]" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="{{$module}}[view]" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="{{$module}}[delete]" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="{{$module}}[approve]" class="form-control">
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>

                                <button class="btn btn-primary mb-3" type="submit" style="float: right;">Submit</button>
                            </form>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection