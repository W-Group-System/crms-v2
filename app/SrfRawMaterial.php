<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class SrfRawMaterial extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;

    protected $table = "srfrawmaterials";
    protected $primaryKey = "Id";

    const UPDATED_AT = "ModifiedDate";
    const CREATED_AT = "CreatedDate";

    protected $fillable = [
        'SampleRequestId',
        'MaterialId',
        'LotNumber',
        'Remarks',
    ];

    public function productMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'MaterialId', 'id');
    }

    public function transformAudit(array $data): array
    {
        if (isset($data['auditable_id'])) {
            $data['auditable_id'] = $this->SampleRequestId;
        }

        return $data;
    }
}
