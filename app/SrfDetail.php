<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SrfDetail extends Model
{
    use SoftDeletes;
    protected $table = "srfdetails";
    const UPDATED_AT = "ModifiedDate";
    const CREATED_AT = "DateCreated";

    const DELETED_AT =  "IsDeleted";

    protected $fillable = [
        'SampleRequestId',
        'DateCreated',
        'UserId',
        'DetailsOfRequest',
    ];

    public function userSupplementary()
    {
        return $this->belongsTo(User::class, 'UserId', 'user_id');
    }
}
