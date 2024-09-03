<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Audit;

class RequestProductEvaluation extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

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

    public function primarySalesPerson()
    {
        return $this->belongsTo(User::class, 'PrimarySalesPersonId', 'user_id');
    }
    public function secondarySalesPerson()
    {
        return $this->belongsTo(User::class, 'SecondarySalesPersonId', 'user_id');
    }
    public function primarySalesPersonById()
    {
        return $this->belongsTo(User::class,'PrimarySalesPersonId','id');
    }
    public function secondarySalesPersonById()
    {
        return $this->belongsTo(User::class,'SecondarySalesPersonId','id');
    }

    public function progressStatus()
    {
        return $this->belongsTo(SrfProgress::class, 'Progress', 'id');
    }
    public function projectName()
    {
        return $this->belongsTo(ProjectName::class, 'ProjectNameId', 'id');
    }
    public function priceCurrency()
    {
        return $this->belongsTo(PriceCurrency::class, 'CurrencyId', 'id');
    }
    
    public function progresses()
    {
        return $this->belongsTo(SrfProgress::class, 'Progress', 'id');
    }

    public function rpePersonnel()
    {
        return $this->hasMany(RpePersonnel::class,'RequestProductEvaluationId','id');
    }
    public function supplementaryDetails()
    {
        return $this->hasMany(RpeDetail::class,'RequestProductEvaluationId','id');
    }
    public function rpeTransactionApprovals()
    {
        return $this->hasMany(TransactionApproval::class,'TransactionId','id')->where('Type', 20)->where('RemarksType', 'approved');
    }
    public function approver()
    {
        return $this->belongsTo(User::class,'ApprovedBy','Id');
    }
    public function rndRpeFiles()
    {
        return $this->hasMany(RpeFile::class, 'RequestProductEvaluationId', 'id')->where('userType', 'RND');
    }
}
