<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ItineraryCountry
 * @package App
 *
 * @property int $id
 * @property int $declaration_id
 * @property string $country_code
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class ItineraryCountry extends Model
{
    /**
     * @return BelongsTo
     */
    public function declaration()
    {
        return $this->belongsTo(Declaration::class);
    }
}
