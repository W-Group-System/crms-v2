<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductEvaluation extends Model
{
    use SoftDeletes;
    protected $table = "product_evaluations";

    public function client() 
    {
        return $this->belongsTo(Client::class);
    }

    public function product_application() 
    {
        return $this->belongsTo(ProductApplication::class, 'application_id', 'id');
    }

    public function primarySalesPerson()
    {
        return $this->belongsTo(User::class,'PrimarySalesPersonId','id');
    }
}
