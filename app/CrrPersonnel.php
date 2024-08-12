<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrrPersonnel extends Model
{
    protected $table = 'crrpersonnels';
    protected $primaryKey = 'Id';

    public function crrPersonnelByUserId()
    {
        return $this->belongsTo(User::class,'PersonnelUserId','user_id');
    }

    public function crrPersonnelById()
    {
        return $this->belongsTo(User::class,'PersonnelUserId','id');
    }
}
