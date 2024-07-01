<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SampleRequest extends Model
{
    use SoftDeletes;
    protected $table = "samplerequests";

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

    public function applications()
    {
        return $this->belongsTo(ProductApplication::class, 'ApplicationId');
    }
}
