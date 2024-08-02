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
        'NatureOfRequestId', 'RefCrrNumber', 'RefRpeNumber', 'DetailsOfRequirement', 'Status', 'Progress'
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

    public function crrNature()
    {
        return $this->hasMany(CrrNature::class,'CustomerRequirementId');
    }

    public function primarySales()
    {
        return $this->belongsTo(User::class, 'PrimarySalesPersonId','user_id');
    }

    public function secondarySales()
    {
        return $this->belongsTo(User::class, 'SecondarySalesPersonId','user_id');
    }

    public function priority()
    {
        return $this->belongsTo(CrrPriority::class,'Priority','Id');
    }
}
