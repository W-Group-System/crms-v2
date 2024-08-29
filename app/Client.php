<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use SoftDeletes;
    protected $table = "clientcompanies";
    protected $fillable = [
        'BuyerCode', 'PrimaryAccountManagerId', 'SapCode', 'SecondaryAccountManagerId',
        'Name', 'TradeName', 'TaxIdentificationNumber', 'TelephoneNumber', 'PaymentTermId',
        'FaxNumber', 'Type', 'Website', 'ClientRegionId', 'Email', 'ClientCountryId',
        'Source', 'ClientAreaId', 'BusinessTypeId', 'ClientIndustryId', 'Status'
    ];

    public function industry() 
    {
        return $this->belongsTo(Industry::class, 'ClientIndustryId');
    }
    
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'CompanyId');
    }

    public function files()
    {
        return $this->hasMany(FileClient::class, 'ClientId');
    }

    public function userById()
    {
        return $this->belongsTo(User::class, 'PrimaryAccountManagerId', 'id');
    }

    public function userById2()
    {
        return $this->belongsTo(User::class, 'SecondaryAccountManagerId', 'id');
    }

    public function userByUserId()
    {
        return $this->belongsTo(User::class, 'PrimaryAccountManagerId', 'user_id');
    }

    public function userByUserId2()
    {
        return $this->belongsTo(User::class, 'SecondaryAccountManagerId', 'user_id');
    }
  
    public function clientregion()
    {
        return $this->belongsTo(ClientRegion::class, 'ClientRegionId');
    }

    public function clientcountry()
    {
        return $this->belongsTo(ClientCountry::class, 'ClientCountryId');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'ClientId', 'id');
    }   

    public function crrClients()
    {
        return $this->hasMany(CustomerRequirement::class, 'ClientId', 'id');
    }

    public function rpeClients()
    {
        return $this->hasMany(RequestProductEvaluation::class, 'ClientId', 'id');
    }   
    
    public function srfClients() 
    {
        return $this->hasMany(SampleRequest::class, 'ClientId', 'id');
    }

    public function srfClientFiles()
    {
        return $this->hasManyThrough(SrfFile::class, SampleRequest::class, 'ClientId', 'SampleRequestId');
    }

    public function sampleRequests()
    {
        return $this->hasMany(SampleRequest::class, 'ClientId', 'id');
    }

    public function crrClientFiles()
    {
        return $this->hasManyThrough(FileCrr::class, CustomerRequirement::class, 'ClientId', 'CustomerRequirementId');
    }

    public function customerRequirements()
    {
        return $this->hasMany(CustomerRequirement::class, 'ClientId', 'id');
    }

    public function rpeClientFiles()
    {
        return $this->hasManyThrough(FileRpe::class, RequestProductEvaluation::class, 'ClientId', 'RequestProductEvaluationId');
    }

    public function productEvaluations()
    {
        return $this->hasMany(RequestProductEvaluation::class, 'ClientId', 'id');
    }

    public function productFiles()
    {
        return $this->hasMany(ProductFiles::class, 'ClientId', 'id');
    }

    public function clientPaymentTerm()
    {
        return $this->belongsTo(PaymentTerms::class, 'PaymentTermId', 'id');
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
