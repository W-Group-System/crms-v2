<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentSample extends Model
{
    use SoftDeletes;

    protected $table = "sse";
    protected $fillable = [
        'SseNumber', 'DateSubmitted', 'AttentionTo', 'RmType', 'Grade', 'ProductCode', 'Origin', 'Supplier'
    ];
}
