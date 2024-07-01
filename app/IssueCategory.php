<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class IssueCategory extends Model
{
    use SoftDeletes;
    protected $table = "customerserviceissuecategories";
    protected $fillable = [
        'Name', 'Description'
    ];
}
