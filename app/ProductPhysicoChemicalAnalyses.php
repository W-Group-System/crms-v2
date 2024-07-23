<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPhysicoChemicalAnalyses extends Model
{
    protected $table = "productdsphysiochemicalanalysis";
    protected $primaryKey = "Id";

    const CREATED_AT = "CreatedDate";
    const UPDATED_AT = "ModifiedDate";
}
