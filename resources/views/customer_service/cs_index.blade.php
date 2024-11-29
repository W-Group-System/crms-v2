@extends('layouts.cs_header')
@section('content')
<div class="col-12 text-center">
    <img src="{{asset('images/whi.png')}}" style="width: 180px;" class="mt-3">
    <h2 class="header_h2 mt-2">Customer Service Application Form</h2>
</div>
<div class="row text-center" style="margin-top: 5em">
    <div class="col-6 text-right">
        <a href="{{ url('/customer_complaint2') }}">
            <div class="btn button">Customer Complaint</div>  
        </a>
    </div>
    <div class="col-6 text-left">
        <a href="{{ url('/customer_satisfaction') }}">
            <div class="btn button">Customer Satisfaction</div>
        </a>
    </div>
</div>
@endsection
