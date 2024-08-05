<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFiles extends Model
{
    protected $table = "productfiles";
    protected $primaryKey = "Id";
    
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'ModifiedDate';

    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientId');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductId', 'id');
    }
}
