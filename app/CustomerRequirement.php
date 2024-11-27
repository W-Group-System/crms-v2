<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerRequirement extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use SoftDeletes;
    protected $table = "customerrequirements";

    const CREATED_AT = "DateCreated";

    protected $fillable = [
        'CrrNumber', 'CreatedDate', 'ClientId', 'Priority', 'ApplicationId', 'DueDate', 'PotentialVolume', 'UnitOfMeasureId',
        'PrimarySalesPersonId', 'TargetPrice', 'CurrencyId', 'SecondarySalesPersonId', 'Competitor', 'CompetitorPrice',
        'NatureOfRequestId', 'RefCrrNumber', 'RefRpeNumber', 'DetailsOfRequirement', 'Status', 'Progress', 'ReturnToSales', 'RefCode', 'File'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientId', 'id');
    }

    public function product_application() 
    {
        return $this->belongsTo(ProductApplication::class, 'ApplicationId', 'id');
    }

    public function crr_personnels() 
    {
        return $this->belongsTo(CrrPersonnel::class, 'id', 'CustomerRequirementId');
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

    public function primarySalesById()
    {
        return $this->belongsTo(User::class, 'PrimarySalesPersonId','id');
    }

    public function secondarySalesById()
    {
        return $this->belongsTo(User::class, 'SecondarySalesPersonId','id');
    }

    public function priority()
    {
        return $this->belongsTo(CrrPriority::class,'Priority','Id');
    }

    public function crrDetails()
    {
        return $this->hasMany(CrrDetail::class,'CustomerRequirementId');
    }

    public function crrPersonnel()
    {
        return $this->hasMany(CrrPersonnel::class,'CustomerRequirementId');
    }

    public function crrFiles()
    {
        return $this->hasMany(FileCrr::class,'CustomerRequirementId');
    }

    public function crrTransactionApprovals()
    {
        return $this->hasMany(TransactionApproval::class,'TransactionId','id')->where('Type', 10);
    }

    public function historyLogs()
    {
        return $this->hasMany(TransactionLogs::class,'TransactionId','id')->where('Type', 10);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class,'TransactionNumber','CrrNumber');
    }

    public function approver()
    {
        return $this->belongsTo(User::class,'ApprovedBy','id');
    }

    public function unitOfMeasure()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'UnitOfMeasureId', 'Id');
    }

    public function price()
    {
        return $this->belongsTo(PriceCurrency::class,'CurrencyId','id');
    }

    public function salesapprovers()
    {
        return $this->belongsTo(SalesApprovers::class, 'PrimarySalesPersonId', 'UserId');
    }
    public function salesapproverByUserId()
    {
        return $this->belongsTo(SalesApprovers::class, 'PrimarySalesPersonId', 'UserByUserId');
    }
}
