<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentSample extends Model
{
    use SoftDeletes;

    protected $table = "sse";
    protected $fillable = [
        'SseNumber', 'DateSubmitted', 'AttentionTo', 'RmType', 'Grade', 'ProductCode', 'Origin', 'Supplier', 'SampleType'
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
}
