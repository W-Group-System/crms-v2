<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class SrfPersonnel extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;

    protected $table = "srfpersonnels";
    protected $primaryKey = "Id";
    protected $fillable = [
        'SampleRequestId',
        'PersonnelType',
        'PersonnelUserId',
        'CreatedDate',
    ];
    public function assignedPersonnel()
    {
        return $this->belongsTo(User::class, 'PersonnelUserId', 'user_id');
    }

    public function transformAudit(array $data): array
    {
        if (isset($data['auditable_id'])) {
            $data['auditable_id'] = $this->SampleRequestId;
        }

        return $data;
    }
}
