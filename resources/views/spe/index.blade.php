@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">Supplier Product Evaluation List</h4>
            <div class="row height d-flex ">
                <div class="col-md-6 mt-2 mb-2">
                    <a href="#" id="copy_prospect_btn" class="btn btn-md btn-outline-info mb-1">Copy</a>
                    <a href="{{url('export_prospect_client')}}" class="btn btn-md btn-outline-success mb-1">Excel</a>
                </div>
                <div class="col-md-6 mt-2 mb-2 text-right">
                    <a href="{{ url('client/create') }}" id="newClient"><button class="btn btn-md btn-outline-primary"><i class="ti ti-plus"></i>&nbsp;New</button></a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block">
                        <select name="number_of_entries" class="form-control" onchange="this.form.submit()">
                            <option value="10" @if($entries == 10) selected @endif>10</option>
                            <option value="25" @if($entries == 25) selected @endif>25</option>
                            <option value="50" @if($entries == 50) selected @endif>50</option>
                            <option value="100" @if($entries == 100) selected @endif>100</option>
                        </select>
                    </form>
                    <span>Entries</span>
                </div>
                <div class="col-lg-6">
                    <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-lg-10">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search Supplier Product" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
            <table class="table table-striped table-bordered table-hover" id="spe_table" width="100%">
                <thead>
                    <tr>
                        <th width="10%">SPE #</th>
                        <th width="30%">Product Name</th>
                        <th width="30%">Suplier/ Trader Name</th>
                        <th width="15%">Attention To</th>
                        <th width="15%">Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection