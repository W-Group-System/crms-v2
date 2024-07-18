<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDataSheet extends Model
{
    protected $table = "productdatasheets";

    public function products()
    {
        return $this->belongsTo(Product::class, 'ProductId');
    }

    public function clients()
    {
        return $this->belongsTo(Client::class, 'CompanyId');
    }
}
