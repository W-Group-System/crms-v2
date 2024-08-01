<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class RequestProductEvaluation extends Model
{
    use SoftDeletes;
    protected $table = "requestproductevaluations";

    // const UPDATED_AT = "ModifiedDate";
    // const CREATED_AT = "CreatedDate";
    // const DELETED_AT =  "IsDeleted";

    protected $fillable = [
        'RpeNumber', 'CreatedDate', 'ClientId', 'Priority', 'ApplicationId', 'DueDate', 'PotentialVolume', 'UnitOfMeasureId',
        'PrimarySalesPersonId', 'TargetRawPrice', 'CurrencyId', 'SecondarySalesPersonId', 'AttentionTo', 'SampleName',
        'NatureOfRequestId', 'Supplier', 'ObjectiveForRpeProject', 'Status', 'Progress', 'ProjectNameId'
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
