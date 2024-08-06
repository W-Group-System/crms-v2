<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PriceMonitoring extends Model
{
    use SoftDeletes;
    protected $table = "pricerequestforms";
    // const CREATED_AT = "CreatedDate";
    
    protected $fillable = [
        'PrfNumber', 'PrimarySalesPersonId', 'SecondarySalesPersonId', 'DateRequested', 'ClientId', 'PriceRequestPurpose', 'ShipmentTerm', 'PaymentTermId',
        'OtherCostRequirements', 'Commission', 'Remarks', 'Progress', 'Status', 'IsWithCommission'
    ];
    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientId', 'id');
    }

    public function product_application() 
    {
        return $this->belongsTo(ProductApplication::class, 'ApplicationId', 'id');
    }
    public function progressStatus()
    {
        return $this->belongsTo(SrfProgress::class, 'Progress', 'id');
    }
    
    public function requestPriceProducts()
    {
        return $this->hasMany(PriceRequestProduct::class, 'PriceRequestFormId', 'id');
    }

    public function primarySalesPerson()
    {
        return $this->belongsTo(User::class, 'PrimarySalesPersonId', 'user_id');
    }
    public function secondarySalesPerson()
    {
        return $this->belongsTo(User::class, 'SecondarySalesPersonId', 'user_id');
    }
    public function paymentterms() 
    {
        return $this->belongsTo(PaymentTerms::class, 'PaymentTermId', 'id');
    }
}
