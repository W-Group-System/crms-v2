<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use SoftDeletes;
    protected $table = "activities";

    protected $fillable = [
        'ActivityNumber', 
        'Type',
        'ClientId',
        'ClientContactId',
        'PrimaryResponsibleUserId',
        'SecondaryResponsibleUserId',
        'RelatedTo',
        'TransactionNumber',
        'ScheduleFrom',
        'ScheduleTo',
        'Title',
        'Description',
        'Status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientId');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'ClientContactId');
    }
    
    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function files()
    {
        return $this->hasOne(FileActivity::class);
    }
}
