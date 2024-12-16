<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpeAttachments extends Model
{
    use SoftDeletes;

    protected $table = "speattachments";
    protected $fillable = [
        'SpeId', 'Name', 'Path', 'IsForReview', 'IsConfidential'
    ];
}
