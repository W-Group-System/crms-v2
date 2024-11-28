<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $table = "suppliers";

    protected $fillable = [
        'Name', 'Products', 'Origin', 'Distributor', 'Address', 'TelNo', 'FaxNo', 'MobileNo',
        'Email', 'Email2', 'Terms', 'Status'
    ];

    public function payment_terms() 
    {
        return $this->belongsTo(PaymentTerms::class, 'Terms', 'id');
    }

    public function supplier_contacts() 
    {
        return $this->hasMany(SupplierContact::class, 'SupplierId', 'Id');
    }
}
