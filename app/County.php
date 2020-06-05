<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class County
 * @package App
 *
 * @property int $id
 * @property int $siruta_id
 * @property string $name
 */
class County extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'counties';

    /**
     * @return BelongsTo
     */
    public function siruta()
    {
        return $this->belongsTo(Siruta::class, 'siruta_id');
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
