<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Audit;

class PriceMonitoring extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "pricerequestforms";
    // const CREATED_AT = "CreatedDate";
    
    protected $fillable = [
        'PrfNumber', 'PrimarySalesPersonId', 'SecondarySalesPersonId', 'DateRequested', 'ClientId', 'ContactId', 'ValidityDate','Moq','ShelfLife', 'PriceRequestPurpose', 
        'ShipmentTerm', 'PaymentTermId', 'OtherCostRequirements', 'Commission', 'Remarks', 'Progress', 'Status', 'IsWithCommission', 'Destination', 'PriceLockPeriod',
        'TaxType', 'PackagingType'
    ];
    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientId', 'id');
    }

    public function clientContact()
    {
        return $this->belongsTo(Contact::class, 'ContactId');
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

    public function productMaterialComposition()
    {
        return $this->hasMany(ProductMaterialsComposition::class, 'ProductId');
    }
}
