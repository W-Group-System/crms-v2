<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Product extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use SoftDeletes;
    protected $table = "products";
    protected $fillable = [
        'ddw_number', 'code', 'reference_no', 'type', 'application_id', 'application_subcategory_id', 'product_origin', 'created_by', 'status'
    ];

    public function userById()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function userByUserId()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function getRelatedUser()
    {
        return User::where('id', $this->created_by)
                   ->orWhere('user_id', $this->created_by)
                   ->first();
    }
    // public function product_raw_materials()
    // {
    //     return $this->hasMany(ProductRawMaterials::class);
    // }

    public function productMaterialComposition()
    {
        return $this->hasMany(ProductMaterialsComposition::class, 'ProductId')->where('IsDeleted', '!=', 1);
    }

    public function productSpecification()
    {
        return $this->hasMany(ProductSpecification::class,'ProductId');
    }

    public function productFiles()
    {
        return $this->hasMany(ProductFiles::class,'ProductId');
    }

    public function productDataSheet()
    {
        return $this->hasOne(ProductDataSheet::class, 'ProductId');
    }

    public function productEventLogs()
    {
        return $this->hasMany(UserEventLogs::class, 'Value', 'code');
    }

    // public function productRps()
    // {
    //     return $this->hasMany(RequestProductEvaluation::class, 'DdwNumber', 'ddw_number');
    // }

    public function sampleRequestProduct()
    {
        return $this->hasMany(SampleRequestProduct::class, 'ProductCode', 'code');
    }

    public function application()
    {
        return $this->belongsTo(ProductApplication::class);
    }

    public function approveById()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
    public function approveByUserId()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }
}
