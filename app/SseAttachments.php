<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SseAttachments extends Model
{
    use SoftDeletes;

    protected $table = "sseattachments";
    protected $fillable = [
        'SseId', 'Name', 'Path', 'IsForReview', 'IsConfidential'
    ];
}
