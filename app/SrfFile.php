<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SrfFile extends Model
{
    use SoftDeletes;
    protected $table = "srffiles";
    const UPDATED_AT = "ModifiedDate";
    const CREATED_AT = "CreatedDate";

    const DELETED_AT =  "IsDeleted";
}
