<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = "clientcompanyaddresses";
    protected $fillable = [
        'CompanyId', 'AddressType', 'Address'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
