@extends('layouts.header')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Welcome back,&nbsp;{{auth()->user()->full_name}}!</h3>
                    <h4 class="font-weight-normal mb-0" style="color: #7d7373">{{ date('l, d F') }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection