<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ComplaintRemarks extends Model
{
    use SoftDeletes;
    protected $table = "ccremarks";
    protected $fillable = ['CcId', 'Path', 'SalesRemarks', 'SalesRemarksBy'];

}
