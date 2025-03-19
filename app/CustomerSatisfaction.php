<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSatisfaction extends Model
{
    protected $table = "customersatisfaction";
    protected $fillable = [
        'CompanyName', 'CsNumber', 'ContactName', 'Concerned', 'Description', 'Category', 'Email', 'ContactNumber', 'Status', 'ReceivedBy', 'DateReceived', 'DateClosed', 'Progress', 'ClosedBy', 'ApprovedBy'
    ];

    public function concerned() 
    {
        return $this->belongsTo(ConcernDepartment::class, 'Concerned', 'id');
    }

    public function category() 
    {
        return $this->belongsTo(IssueCategory::class, 'Category', 'id');
    }

    public function cs_attachments() 
    {
        return $this->hasMany(CsFiles::class, 'CsId', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'ReceivedBy', 'id');
    }

    public function notedBy()
    {
        return $this->belongsTo(User::class, 'NotedBy', 'id');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'ClosedBy', 'id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'ApprovedBy', 'id');
    }

    public function clientCompany()
    {
        return $this->hasOne(Client::class, 'Name', 'CompanyName');
    }

    public function salesapprovers()
    {
        return $this->belongsTo(SalesApprovers::class, 'ReceivedBy', 'UserId'); 
    }
}
