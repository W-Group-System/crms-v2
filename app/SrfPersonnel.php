<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SrfPersonnel extends Model
{
    protected $table = "srfpersonnels";
    protected $fillable = [
        'SampleRequestId',
        'PersonnelType',
        'PersonnelUserId',
        'CreatedDate',
    ];
    public function assignedPersonnel()
    {
        return $this->belongsTo(User::class, 'PersonnelUserId', 'user_id');
    }
}
