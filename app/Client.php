<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use SoftDeletes;
    protected $table = "clientcompanies";
    protected $fillable = [
        'BuyerCode', 'PrimaryAccountManagerId', 'SapCode', 'SecondaryAccountManagerId', 'Name', 'TradeName', 'TaxIdentificationNumber', 'TelephoneNumber', 'PaymentTermId', 'FaxNumber', 'Type', 'Website', 'ClientRegionId', 'Email', 'ClientCountryId', 'Source', 'ClientAreaId', 'BusinessTypeId', 'ClientIndustryId', 'Status'
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
        return $this->hasMany(Contact::class);
    }

    public function files()
    {
        return $this->hasMany(FileClient::class);
    }
}
