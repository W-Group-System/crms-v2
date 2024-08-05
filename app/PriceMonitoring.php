<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PriceMonitoring extends Model
{
    use SoftDeletes;
    protected $table = "pricerequestforms";

    
    protected $fillable = [
        'PrfNumber', 'PrimarySalesPersonId', 'SecondarySalesPersonId', 'DateRequested', 'ClientId', 'PriceRequestPurpose', 'ShipmentTerm', 'PaymentTermId',
        'OtherCostRequirements', 'Commission', 'Remarks'
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
}
