<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PaymentTerms extends Model
{
    use SoftDeletes;
    protected $table = "clientpaymentterms";
    protected $fillable = [
        'Type', 'Name', 'Description'
    ];
}
