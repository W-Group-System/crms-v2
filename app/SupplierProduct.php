<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierProduct extends Model
{
    use SoftDeletes;

    protected $table = "spe";
    protected $fillable = [
        'ProductName', 'DateRequested', 'AttentionTo', 'Deadline', 'Manufacturer', 'Quantity', 'Supplier', 'ProductApplication', 'Origin', 'LotNo', 'Price', 'ApprovedBy', 'Progress', 'Reconfirmatory', 'RejectedRemarks', 'LabRemarks'
    ];

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'Supplier', 'Id');
    }

    // public function supplier_instruction() 
    // {
    //     return $this->hasMany(SpeInstructions::class, 'SpeId', 'id');
    // }
    
    public function supplier_instruction()
    {
        return $this->hasMany(SpeInstructions::class, 'SpeId', 'id');  // one-to-many relationship
    }

    public function supplier_disposition() 
    {
        return $this->hasMany(SpeDisposition::class, 'SpeId', 'id');
    }

    public function supplier_files() 
    {
        return $this->hasMany(SpeAttachments::class, 'SpeId', 'id');
    }

    public function attachments() 
    {
        return $this->hasMany(SpeFiles::class, 'SpeId', 'id');
    }

    public function progress() 
    {
        return $this->belongsTo(StProgress::class, 'Progress', 'id');
    }

    public function prepared_by() 
    {
        return $this->belongsTo(User::class, 'PreparedBy', 'id');
    }

    public function approved_by() 
    {
        return $this->belongsTo(User::class, 'ApprovedBy', 'id');
    }

    public function spePersonnel()
    {
        return $this->hasMany(SpePersonnel::class,'SpeId');
    }

    public function historyLogs()
    {
        return $this->hasMany(TransactionLogs::class,'TransactionId','id')->where('Type', 40);
    }

    public function spe_personnels() 
    {
        return $this->belongsTo(SpePersonnel::class, 'id', 'SpeId');
    }
}
