<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SpePersonnel extends Model
{
    use SoftDeletes;
    protected $table = "spepersonnels";
    protected $fillable = [
        'SpeId', 'SpePersonnel'
    ];

    public function crrPersonnelById()
    {
        return $this->belongsTo(User::class,'SpePersonnel','id');
    }
}
