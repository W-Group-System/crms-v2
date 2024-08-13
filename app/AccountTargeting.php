<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AccountTargeting extends Model
{
    use SoftDeletes;
    protected $table = "clientaccounttargetmodelsession";

    protected $primaryKey = "Id";


}
