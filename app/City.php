<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class City
 * @package App
 *
 * @property int $id
 * @property int $siruta_id
 * @property int $siruta_parent_id
 * @property int $county_id
 * @property string $name
 */
class City extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities';

    /**
     * @return BelongsTo
     */
    public function siruta()
    {
        return $this->belongsTo(Siruta::class, 'siruta_id');
    }

    /**
     * @return BelongsTo
     */
    public function county()
    {
        return $this->belongsTo(County::class, 'county_id');
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'siruta' => $this->siruta_id,
            'name' => $this->name
        ];
    }
}
