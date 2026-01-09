<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class RpePersonnel extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;

    protected $table = "rpepersonnels";

    const UPDATED_AT = "ModifiedDate";
    const CREATED_AT = "CreatedDate";
    protected $primaryKey = "Id";
    protected $fillable = [
        'RequestProductEvaluationId',
        'PersonnelType',
        'PersonnelUserId',
        'CreatedDate',
    ];
    public function assignedPersonnel()
    {
        return $this->belongsTo(User::class, 'PersonnelUserId', 'user_id');
    }
    public function userId()
    {
        return $this->belongsTo(User::class,'PersonnelUserId','id');
    }

    public function transformAudit(array $data): array
    {
        if (isset($data['auditable_id'])) {
            $data['auditable_id'] = $this->RequestProductEvaluationId;
        }

        return $data;
    }
    public function rpePersonnelByUserId()
    {
        return $this->belongsTo(User::class,'PersonnelUserId','user_id');
    }

    public function rpePersonnelById()
    {
        return $this->belongsTo(User::class,'PersonnelUserId','id');
    }
}
