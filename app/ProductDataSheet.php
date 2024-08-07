<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProductDataSheet extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    
    protected $table = "productdatasheets";
    protected $primaryKey = "Id";

    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'ModifiedDate';

    public function products()
    {
        return $this->belongsTo(Product::class, 'ProductId');
    }

    public function clients()
    {
        return $this->belongsTo(Client::class, 'CompanyId');
    }

    public function productPhysicoChemicalAnalyses()
    {
        return $this->hasMany(ProductPhysicoChemicalAnalyses::class, 'ProductDataSheetId')->orderBy('id', 'asc');
    }

    public function productMicrobiologicalAnalysis()
    {
        return $this->hasMany(ProductMicrobiologicalAnalysis::class, 'ProductDataSheetId')->orderBy('id', 'asc');
    }

    public function productHeavyMetal()
    {
        return $this->hasMany(ProductHeavyMetal::class, 'ProductDataSheetId')->orderBy('id', 'asc');
    }
    public function productNutritionalInformation()
    {
        return $this->hasMany(ProductNutrionalInformation::class, 'ProductDataSheetId')->orderBy('id', 'asc');
    }
    public function productAllergens()
    {
        return $this->hasMany(ProductAllergens::class, 'ProductDataSheetId')->orderBy('id', 'asc');
    }
    public function productPotentialBenefit()
    {
        return $this->hasMany(ProductPotentialBenefit::class, 'ProductDataSheetId')->orderBy('id', 'asc');
    }
}
