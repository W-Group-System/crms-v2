@extends('layouts.cs_header')
@section('content')
<div class="col-12 text-center">
    <img src="{{asset('images/wgroup1.png')}}" style="width: 180px;">
    <h2 class="header_h2 mt-2">Customer Service Application Form</h2>
</div>
<div class="row text-center" style="margin-top: 5em">
    <div class="col-6 text-right">
        <div class="btn button">Customer Complaint</div>  
    </div>
    <div class="col-6 text-left">
        <a href="{{ url('/customer_satisfaction') }}">
            <div class="btn button">Customer Feedback</div>
        </a>
    </div>
</div>
@endsection
