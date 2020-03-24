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
 * @property int $user_id
 * @property int|null $city_id
 * @property int|null $county_id
 * @property string $city_full_address
 * @property DateTime $city_arrival_date
 * @property DateTime|null $city_departure_date
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class IsolationAddress extends Model
{
    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}