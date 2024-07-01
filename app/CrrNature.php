<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class CrrNature extends Model
{
    protected $table = "crrnatureofrequests";
    protected $fillable = [
        'CustomerRequirementId', 'NatureOfRequestId'
    ];
}
