<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SsePersonnel extends Model
{
    use SoftDeletes;
    protected $table = "ssepersonnels";
    protected $fillable = [
        'SseId', 'SsePersonnel'
    ];

    public function ssePersonnelById()
    {
        return $this->belongsTo(User::class,'SsePersonnel','id');
    }
}
