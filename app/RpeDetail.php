<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class RpeDetail extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;

    protected $table = "rpedetails";
    protected $primaryKey = "Id";
    const UPDATED_AT = "ModifiedDate";
    const CREATED_AT = "DateCreated";

    protected $fillable = [
        'RequestProductEvaluationId',
        'DateCreated',
        'UserId',
        'DetailsOfRequest',
    ];

    public function userSupplementary()
    {
        return $this->belongsTo(User::class, 'UserId', 'user_id');
    }

    public function userId()
    {
        return $this->belongsTo(User::class, 'UserId', 'id');
    }

    public function transformAudit(array $data): array
    {
        if (isset($data['auditable_id'])) {
            $data['auditable_id'] = $this->RequestProductEvaluationId;
        }

        return $data;
    }
}
