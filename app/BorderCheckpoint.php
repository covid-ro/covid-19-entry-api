<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class BorderCheckpoint
 * @package App
 *
 * @property int $id
 * @property string|null $code
 * @property string $name
 * @property bool $is_dsp_before_border
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class BorderCheckpoint extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'is_dsp_before_border' => (bool)$this->is_dsp_before_border,
            'status' => empty($this->deleted_at) ? self::STATUS_ACTIVE : self::STATUS_INACTIVE
        ];
    }
}
