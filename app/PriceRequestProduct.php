<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PriceRequestProduct extends Model
{
    use SoftDeletes;
    protected $table = "pricerequestproducts";
    protected $primaryKey = "Id";

    const UPDATED_AT = "ModifiedDate";
    const CREATED_AT = "CreatedDate";

    protected $fillable = [
        'Type', 'QuantityRequired', 'ProductId', 'ProductRmc', 'IsalesShipmentCost', 'IsalesFinancingCost', 'IsalesOthers', 'IsalesTotalBaseCost',
        'IsalesBaseSellingPrice', 'IsalesOfferedPrice', 'IsalesMargin', 'IsalesMarginPercentage'
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
