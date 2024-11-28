<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentSample extends Model
{
    use SoftDeletes;

    protected $table = "sse";
    protected $fillable = [
        'SseNumber', 'DateSubmitted', 'AttentionTo', 'RmType', 'Grade', 'ProductCode', 'Origin', 'Supplier', 'SampleType', 'SseResult', 'PoNumber', 'Ordered', 'Quantity', 'ProductOrdered', 'OtherProduct', 'Buyer', 'BuyersPo', 'Instruction', 'SalesAgreement', 'ProductDeclared', 'LnBags', 'SampleType', 'LotNumber', 'QtyRepresented', 'Name', 'Path', 'Work', 
    ];

    public function shipment_pack() 
    {
        return $this->hasMany(SsePacks::class, 'SseId', 'id');
    }
    
    public function shipment_attachments() 
    {
        return $this->hasMany(SseFiles::class, 'SseId', 'id');
    }

    public function shipment_work() 
    {
        return $this->hasMany(SseWork::class, 'SseId', 'id');
    }

    public function progress() 
    {
        return $this->belongsTo(StProgress::class, 'Progress', 'id');
    }

    public function prepared_by() 
    {
        return $this->belongsTo(User::class, 'PreparedBy', 'id');
    }
    
    public function approved_by() 
    {
        return $this->belongsTo(User::class, 'ApprovedBy', 'id');
    }

    public function ssePersonnel()
    {
        return $this->hasMany(SsePersonnel::class,'SseId');
    }

    public function historyLogs()
    {
        return $this->hasMany(TransactionLogs::class,'TransactionId','id')->where('Type', 60);
    }
}
