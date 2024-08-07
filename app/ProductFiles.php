<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProductFiles extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
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
