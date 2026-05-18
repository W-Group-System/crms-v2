<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SatisfactionFile extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = "satisfaction_files";
    protected $fillable = ['CsId', 'Path'];

    public function customerSatisfaction()
    {
        return $this->belongsTo(CustomerSatisfaction::class, 'CsId');
    }
}
