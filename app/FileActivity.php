<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileActivity extends Model
{
    protected $table = "actfiles";
    protected $fillable = [
        'activity_id', 'path'
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }
}
