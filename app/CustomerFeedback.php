<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    use SoftDeletes;
    protected $table = "customerservices";

    protected $fillable = [
        'ServiceNumber', 
        'Type',
        'DateReceived',
        'ClientId',
        'ClientContactId',
        'Title',
        'Description',
        'Status',
        'IssueCategoryId',
        'Severity',
        'Etc',
        'ConcernedDepartmentId',
        'Classification'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientId');
    }

    public function contacts()
    {
        return $this->belongsTo(Contact::class, 'ClientContactId');
    }
    public function departments()
    {
        return $this->belongsTo(ConcernDepartment::class, 'ConcernedDepartmentId');
    }
}
