<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Categorization extends Model
{
    use SoftDeletes;
    protected $table = "srfcategorizations";

    const UPDATED_AT = "ModifiedDate";
    const CREATED_AT = "CreatedDate";
    const DELETED_AT =  "IsDeleted";
    protected $fillable = [
        'Name', 'Description'
    ];
}
