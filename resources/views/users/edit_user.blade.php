<div class="modal fade" id="editUser-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_user" action="{{url('update_user/'.$user->id)}}" onsubmit="show()">
                    @csrf
                    <div class="form-group">
                        <label for="name">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Enter Username" value="{{$user->username}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" name="full_name" placeholder="Enter Full Name" value="{{$user->full_name}}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Email Address</label>
                        <input type="text" class="form-control" name="email" placeholder="Enter Email Address" value="{{$user->email}}" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control js-example-basic-single" name="role_id" style="position: relative !important" title="Select Role" required>
                            <option value="" disabled selected>Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @if($role->id == $user->role_id) selected @endif>{{ $role->department->department_code.' - ' .$role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <select class="form-control js-example-basic-single" name="company_id" style="position: relative !important" title="Select Company" required>
                            <option value="" disabled selected>Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @if($company->id == $user->company_id) selected @endif>{{ $company->code.' - '.$company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select class="form-control js-example-basic-single" name="department_id" style="position: relative !important" title="Select Company" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @if($department->id == $user->department_id) selected @endif>{{ $department->department_code.' - '.$department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="formStatus" >
                        <label for="name">Status</label>
                        <select class="form-control js-example-basic-single" name="is_active"  style="position: relative !important" title="Select Type" required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="0" @if($user->is_active == 0) selected @endif>Inactive</option>
                            <option value="1" @if($user->is_active == 1) selected @endif>Active</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">User Approvers</label>
                        @php
                            $salesApprover = $user->salesApproverById->pluck('SalesApproverId')->toArray();
                            $rndApprover = $user->rndApproverById->pluck('RndApproverId')->toArray();
                        @endphp
                        <select class="form-control js-example-basic-multiple" name="user_approvers[]" style="position: relative !important" multiple>
                            @foreach ($approvers as $approver)
                                @if(collect($salesApprover)->isNotEmpty())
                                <option value="{{$approver->id}}" @if((in_array($approver->id, $salesApprover)) || (in_array($approver->user_id, $salesApprover))) selected @endif>{{$approver->full_name}}</option>
                                @elseif(collect($rndApprover)->isNotEmpty())
                                <option value="{{$approver->id}}" @if((in_array($approver->id, $rndApprover)) || (in_array($approver->user_id, $rndApprover))) selected @endif>{{$approver->full_name}}</option>
                                @else
                                <option value="{{$approver->id}}">{{$approver->full_name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Secondary Sales Approvers</label>
                        @php
                            $secondary_person = $user->secondarySalesPerson->pluck('SecondarySalesPersonId')->toArray();
                            
                        @endphp
                        <select class="form-control js-example-basic-multiple" name="secondary_sales[]" style="position: relative !important" multiple>
                            @foreach ($sales as $sale)
                                <option value="{{$sale->id}}" @if(in_array($sale->id, $secondary_person)) selected @endif>{{$sale->full_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Groups</label>
                        @php
                            $group_sales = $user->groupSales->pluck('members')->toArray();
                        @endphp
                        <select class="form-control js-example-basic-multiple" name="group_sales[]" style="position: relative !important" multiple>
                            @foreach ($sales as $sale)
                                <option value="{{$sale->id}}" @if(in_array($sale->id, $group_sales)) selected @endif>{{$sale->full_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>