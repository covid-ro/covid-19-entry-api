<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class IsolationAddress
 * @package App
 *
 * @property int $id
 * @property int $declaration_id
 * @property int|null $county_id
 * @property int|null $settlement_id
 * @property string|null $street
 * @property string|null $number
 * @property string|null $bloc
 * @property string|null $entry
 * @property string|null $apartment
 * @property DateTime|null $city_arrival_date
 * @property DateTime|null $city_departure_date
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class IsolationAddress extends Model
{
    /**
     * @return BelongsTo
     */
    public function declaration()
    {
        return $this->belongsTo(Declaration::class);
    }

    /**
     * @return BelongsTo
     */
    public function county()
    {
        return $this->belongsTo(County::class);
    }

    /**
     * @return BelongsTo
     */
    public function settlement()
    {
        return $this->belongsTo(Settlement::class);
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'city' => !empty($this->settlement) ? $this->settlement->name : null,
            'county' => !empty($this->county) ? $this->county->name : null,
            'street' => $this->street,
            'number' => $this->number,
            'bloc' => $this->bloc,
            'entry' => $this->entry,
            'apartment' => $this->apartment
        ];
    }
}
