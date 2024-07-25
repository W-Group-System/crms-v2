<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use SoftDeletes;
    protected $table = "clientcontacts";
    protected $fillable = [
        'CompanyId', 'ContactName', 'Designation', 'PrimaryTelephone', 'SecondaryTelephone', 'PrimaryMobile', 'SecondaryMobile', 'EmailAddress', 'Skype', 'Viber', 'Facebook', 'WhatsApp', 'LinkedIn', 'Birthday'
    ];

    public function client() 
    {
        return $this->belongsTo(Client::class);
    }
}
