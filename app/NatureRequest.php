<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class NatureRequest extends Model
{
    use SoftDeletes;
    protected $table = "natureofrequests";
    protected $fillable = [
        'Name', 'Description'
    ];
}
