<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Audit;

class SampleRequest extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "samplerequests";
    protected $primaryKey = "Id";
    

    protected $fillable = [
        'SrfNumber', 
        'DateRequested',
        'DateRequired',
        'DateStarted',
        'PrimarySalesPersonId',
        'SecondarySalesPersonId',
        'SoNumber',
        'RefCode',
        'Status',
        'Progress',
        'SrfType',
        'ClientId',
        'ContactId',
        'InternalRemarks',
        'Courier',
        'AwbNumber',
        'DateDispatched',
        'DateSampleReceived',
        'DeliveryRemarks',
        'Note'

        
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientId');
    }

    public function productApplicationsId()
    {
        return $this->belongsTo(ProductApplication::class, 'ApplicationId', 'id');
    }

    public function primarySalesPerson()
    {
        return $this->belongsTo(User::class, 'PrimarySalesPersonId', 'user_id');
    }
    public function primarySalesById()
    {
        return $this->belongsTo(User::class, 'PrimarySalesPersonId','id');
    }
    public function secondarySalesPerson()
    {
        return $this->belongsTo(User::class, 'SecondarySalesPersonId', 'user_id');
    }
    public function progressStatus()
    {
        return $this->belongsTo(SrfProgress::class, 'Progress', 'id');
    }
    public function clientContact()
    {
        return $this->belongsTo(Contact::class, 'ContactId');
    }

    public function requestProducts()
    {
        return $this->hasMany(SampleRequestProduct::class, 'SampleRequestId', 'Id');
    }
    public function salesSrfFiles()
    {
        return $this->hasMany(SrfFile::class, 'SampleRequestId', 'Id')->where('userType', 'Sales');
    }
    public function rndSrfFiles()
    {
        return $this->hasMany(SrfFile::class, 'SampleRequestId', 'Id')->where('userType', 'RND');
    }
    public function srfPersonnel()
    {
        return $this->hasMany(SrfPersonnel::class,'SampleRequestId');
    }

    public function srfTransactionApprovals()
    {
        return $this->hasMany(TransactionApproval::class,'TransactionId','Id')->where('Type', 30);
    }
    public function approver()
    {
        return $this->belongsTo(User::class,'ApprovedBy','Id');
    }
    
}
