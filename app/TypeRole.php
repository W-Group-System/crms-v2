<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeRole extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'type_roles';

    protected $fillable = [
        'id',
        'roleType',
        'description',
        'status',
        'deleted_at',
        'created_by',
    ];
}
