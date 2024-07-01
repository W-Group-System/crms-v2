<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
}
