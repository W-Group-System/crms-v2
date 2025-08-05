<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SatisfactionRemarks extends Model
{
    use SoftDeletes;
    protected $table = "cs_remarks";

    public function user()
    {
        return $this->belongsTo(User::class, 'RemarksBy', 'id');
    }
    
}
