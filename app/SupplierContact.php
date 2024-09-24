<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SupplierContact extends Model
{
    use SoftDeletes;
    protected $table = "suppliercontacts";
    protected $fillable = [
        'SupplierId', 'ContactPerson'
    ];
}
