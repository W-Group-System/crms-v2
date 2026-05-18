<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SatisfactionRemarks extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    protected $table = "cs_remarks";

    public function user()
    {
        return $this->belongsTo(User::class, 'RemarksBy', 'id');
    }
    
}
