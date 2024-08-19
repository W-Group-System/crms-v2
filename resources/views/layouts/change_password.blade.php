@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">Change Password</h4>
            <form method="POST" enctype="multipart/form-data" action="{{ route('update_password') }}">
            @csrf
                @if(session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                {{-- <div class="form-group mb-10">
                    <label for="oldPasswordInput">Old Password</label> 
                    <input name="old_password" type="password" placeholder="Enter Old Password" class="form-control @if($errors->has('old_password')) is-invalid @endif" id="oldPasswordInput" value="{{}}">
                    @if($errors->has('old_password'))
                        <span class="text-danger">{{ $errors->first('old_password') }}</span>
                    @endif
                </div> --}}
                <div class="form-group mb-10">
                    <label for="newPasswordInput">New Password</label> 
                    <input name="new_password" type="password" placeholder="Enter New Password" class="form-control @if($errors->has('new_password')) is-invalid @endif" id="newPasswordInput">
                    @if($errors->has('new_password'))
                        <span class="text-danger">{{ $errors->first('new_password') }}</span>
                    @endif
                </div>
                <div class="form-group mb-10">
                    <label for="confirmedPasswordInput">Confirm New Password</label> 
                    <input name="new_password_confirmation" type="password" placeholder="Confirm New Password" class="form-control" id="confirmedPasswordInput">
                </div>
                <div align="right" class="mt-10">
                    <a href="{{ url('/dashboard') }}" class="btn btn-secondary">Close</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection