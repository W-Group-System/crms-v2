<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplaintFile extends Model
{
    use SoftDeletes;
    protected $table = "complaint_files";
    protected $fillable = ['CcId', 'Path']; 

    public function customerComplaint()
    {
        return $this->belongsTo(CustomerComplaint2::class, 'CsId');
    }
}
