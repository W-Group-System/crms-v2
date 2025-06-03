<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerComplaint2 extends Model
{
    protected $table = "customercomplaint";
    protected $fillable = [
        'CompanyName', 'CcNumber', 'ContactName', 'Email', 'Address', 'Country', 'Telephone', 'Moc', 'QualityClass', 'ProductName', 'Description', 'Currency', 'CustomerRemarks', 'SiteConcerned', 'Department', 'Status', 'Progress'
    ];

    public function concerned() 
    {
        return $this->belongsTo(ConcernDepartment::class, 'Department', 'Name');
    }

    public function product_quality()
    {
        return $this->belongsTo(CcProductQuality::class, 'id', 'CcId');
    }

    public function packaging()
    {
        return $this->belongsTo(CcPackaging::class, 'id', 'CcId');
    }

    public function delivery_handling()
    {
        return $this->belongsTo(CcDeliveryHandling::class, 'id', 'CcId');
    }

    public function others()
    {
        return $this->belongsTo(CcOthers::class, 'id', 'CcId');
    }

    public function country()
    {
        return $this->belongsTo(ClientCountry::class, 'Country', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'ReceivedBy', 'id');
    }

    public function action_responsible()
    {
        return $this->belongsTo(User::class, 'ActionResponsible', 'id');
    }

    public function noted_by()
    {
        return $this->belongsTo(User::class, 'NotedBy', 'id');
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class, 'ApprovedBy', 'id');
    }

    public function closed()
    {
        return $this->belongsTo(User::class, 'ClosedBy', 'id');
    }

    public function clientCompany()
    {
        return $this->belongsTo(Client::class, 'CompanyName', 'Name'); 
    }

    public function salesapprovers()
    {
        return $this->belongsTo(SalesApprovers::class, 'ReceivedBy', 'UserId'); 
    }

    public function salesapprovers1()
    {
        return $this->belongsTo(SalesApprovers::class, 'NotedBy', 'UserId'); 
    }
    
    public function files()
    {
        return $this->hasMany(ComplaintFile::class,'CcId');
    }

    public function cc_attachments() 
    {
        return $this->hasMany(ComplaintFile::class, 'CcId', 'id');
    }

    public function objective()
    {
        return $this->hasMany(CcObjectiveFile::class,'CcId');
    }

    public function verification()
    {
        return $this->hasMany(CcVerificationFile::class,'CcId');
    }
}
