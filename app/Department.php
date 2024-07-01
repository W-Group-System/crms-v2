<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use SoftDeletes;
    protected $table = "departments";
    protected $fillable = [
        'company_id', 'name', 'description'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
