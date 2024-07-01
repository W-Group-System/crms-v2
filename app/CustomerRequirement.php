<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CustomerRequirement extends Model
{
    use SoftDeletes;
    protected $table = "customerrequirements";
    protected $fillable = [
        'CrrNumber', 'CreatedDate', 'ClientId', 'Priority', 'ApplicationId', 'DueDate', 'PotentialVolume', 'UnitOfMeasureId',
        'PrimarySalesPersonId', 'TargetPrice', 'CurrencyId', 'SecondarySalesPersonId', 'Competitor', 'CompetitorPrice',
        'NatureOfRequestId', 'RefCrrNumber', 'RefRpeNumber', 'DetailsOfRequirement'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientId', 'id');
    }

    public function product_application() 
    {
        return $this->belongsTo(ProductApplication::class, 'ApplicationId', 'id');
    }

}
