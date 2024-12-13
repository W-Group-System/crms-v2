<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ConcernDepartment extends Model
{
    use SoftDeletes;
    use Notifiable;

    protected $table = "customerserviceconcerneddepartm";
    protected $fillable = [
        'Name', 'Description'
    ];

    public function audit()
    {
        return $this->hasMany(CcEmail::class,'concern_department_id');
    }
}
