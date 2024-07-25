<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class FileClient extends Model
{
    use SoftDeletes;
    protected $table = "clientfiles";
    protected $fillable = [
        'ClientId', 'FileName', 'Path'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientId', 'id');
    }
}
