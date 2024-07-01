@extends('layouts.header')
@section('content')
<style>
    #form_product {
        padding: 20px 20px;
    }
    #productTab .nav-link {
        padding: 15px;
    }
    .form-group label {
    font-size: 0.875rem;
    line-height: 0 !important;
    vertical-align: top;
    margin-bottom: 0 !important;
    }
    .group-form{
        margin-bottom: 1rem !important;
        margin-top: 1rem !important;
    }
</style>
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6"> 
                    <h4 class="card-title d-flex justify-content-between align-items-center" style="margin-top: 10px">View Product Details</h4>
                </div>
                <div class="col-lg-6" align="right">
                    <a href="{{ url('/customer_complaint') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>
                </div>
            </div>
            <form class="form-horizontal" id="form_product" enctype="multipart/form-data">
                <div class="group-form">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Type:</b></label>
                    <label class="col-sm-3 col-form-label">@if($CustomerComplaint->Type == 20)
                        Customer Complaint
                    @endif</label>
                    <label class="offset-sm-2 col-sm-2 col-form-label"><b>Issue Category:</b></label>
                    <label class="col-sm-2 col-form-label">{{ $CustomerComplaint->categories->IssueCategoryId }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>#:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $CustomerComplaint->ServiceNumber  }}</label>
                    <label class="offset-sm-2 col-sm-2 col-form-label"><b>Severity:</b></label>
                    <label class="col-sm-2 col-form-label"> @if($CustomerComplaint->Severity == 10)
                        Minor
                        @elseif($CustomerComplaint->Severity == 20)
                        Minor
                        @elseif($CustomerComplaint->Severity == 30)
                        Critical
                    @endif</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Date Received:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $CustomerComplaint->DateReceived  }}</label>
                    <label class="offset-sm-2 col-sm-2 col-form-label"><b>Etc:</b></label>
                    <label class="col-sm-2 col-form-label">{{ $CustomerComplaint->Etc  }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Received By:</b></label>
                    <label class="col-sm-9 col-form-label"></label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Concerned Department:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $CustomerComplaint->departments->Name }}</label>
                    <label class="offset-sm-2 col-sm-2 col-form-label"><b>Status:</b></label>
                    <label class="col-sm-2 col-form-label">@if($CustomerComplaint->Status == 10)
                        Open
                    @else
                        Closed
                    @endif
                    </label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b></b></label>
                    <label class="col-sm-3 col-form-label"></label>
                    <label class="offset-sm-2 col-sm-2 col-form-label"><b>Date Closed:</b></label>
                    <label class="col-sm-2 col-form-label">{{ $CustomerComplaint->DateClosed  }}</label>
                </div>
            </div>
            <div class="group-form">
                
              
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Client:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $CustomerComplaint->client->Name }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Contact:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $CustomerComplaint->contacts->ContactName }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Telephone:</b></label>
                    <label class="col-sm-3 col-form-label"> 
                        @if(!empty($CustomerComplaint->contacts->PrimaryTelephone))
                        {{ $CustomerComplaint->contacts->PrimaryTelephone }}
                        @elseif(!empty($CustomerComplaint->contacts->SecondaryTelephone))
                        {{ $CustomerComplaint->contacts->SecondaryTelephone }}
                    @endif</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Mobile:</b></label>
                    <label class="col-sm-3 col-form-label"> 
                        @if(!empty($CustomerComplaint->contacts->PrimaryMobile))
                        {{ $CustomerComplaint->contacts->PrimaryMobile }}
                        @elseif(!empty($CustomerComplaint->contacts->SecondaryMobile))
                        {{ $CustomerComplaint->contacts->SecondaryMobile }}
                    @endif</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Email:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $CustomerComplaint->contacts->EmailAddress }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Contact Skype:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $CustomerComplaint->contacts->Skype }}</label>
                </div>
            </div>
            <div class="group-form">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Title:</b></label>
                    <label class="col-sm-10 col-form-label">{{ $CustomerComplaint->Title}}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Description:</b></label>
                    <p class="col-sm-10 col-form-label">{{ $CustomerComplaint->Description}}</p>
                </div>
            </div>
            <div class="group-form">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Corrective Action:</b></label>
                    <p class="col-sm-10 col-form-label">{{ $CustomerComplaint->Response}}</label>
                </div>
            </div>
            </form>          
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="activities-tab" data-toggle="tab" href="#activities" role="tab" aria-controls="activities" aria-selected="true">Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="activities" role="tabpanel" aria-labelledby="activities-tab">...</div>
                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">...</div>
            </div>
        </div>
    </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
   
</script>
@endsection