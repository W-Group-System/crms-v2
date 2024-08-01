<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientRegion extends Model
{
    protected $table = "clientregions";

    public function clients()
    {
        return $this->hasMany(Client::class, 'ClientRegionId');
    }
}
