<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientCountry extends Model
{
    protected $table = "clientcountries";

    public function clients()
    {
        return $this->hasMany(Client::class, 'ClientCountryId');
    }
}
