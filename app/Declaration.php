<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Declaration
 * @package App
 *
 * @property int $id
 * @property int $user_id
 * @property int $declarationcode_id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property string $cnp
 * @property string $document_type
 * @property string $document_series
 * @property string $document_number
 * @property string $travelling_from_country_code
 * @property string $travelling_from_city
 * @property DateTime $travelling_from_date
 * @property DateTime $home_country_return_date
 * @property string $question_1_answer
 * @property string $question_2_answer
 * @property string $question_3_answer
 * @property bool $symptom_fever
 * @property bool $symptom_swallow
 * @property bool $symptom_breathing
 * @property bool $symptom_cough
 * @property string $vehicle_type
 * @property string|null $vehicle_registration_no
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class Declaration extends Model
{
    use SoftDeletes;

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function declarationCode()
    {
        return $this->belongsTo(DeclarationCode::class);
    }

    /**
     * @return HasMany
     */
    public function isolationAddresses()
    {
        return $this->hasMany(IsolationAddress::class);
    }

    /**
     * @return HasMany
     */
    public function itineraryCountries()
    {
        return $this->hasMany(ItineraryCountry::class);
    }
}
