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
 * @property int $border_checkpoint_id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property string $cnp
 * @property DateTime|null $birth_date
 * @property string|null $sex
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
    public function declarationcode()
    {
        return $this->belongsTo(DeclarationCode::class);
    }

    /**
     * @return BelongsTo
     */
    public function bordercheckpoint()
    {
        return $this->belongsTo(BorderCheckpoint::class, 'border_checkpoint_id');
    }

    /**
     * @return HasMany
     */
    public function isolationaddresses()
    {
        return $this->hasMany(IsolationAddress::class);
    }

    /**
     * @return HasMany
     */
    public function itinerarycountries()
    {
        return $this->hasMany(ItineraryCountry::class);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $declarationData = [];
        $declarationData['code'] = $this->declarationcode->code;
        $declarationData['phone'] = $this->user->phone_number;
        $declarationData['name'] = $this->name;
        $declarationData['surname'] = $this->surname;
        $declarationData['email'] = $this->email;
        $declarationData['cnp'] = $this->cnp;
        $declarationData['birth_date'] = $this->birth_date;
        $declarationData['sex'] = $this->sex;

        $declarationData['border_checkpoint'] = !empty($this->bordercheckpoint) ? $this->bordercheckpoint->name : null;
        $declarationData['document_type'] = $this->document_type;
        $declarationData['document_series'] = $this->document_series;
        $declarationData['document_number'] = $this->document_number;

        $declarationData['travelling_from_country_code'] = $this->travelling_from_country_code;
        $declarationData['travelling_from_city'] = $this->travelling_from_city;
        $declarationData['travelling_from_date'] = $this->travelling_from_date;
        $declarationData['home_country_return_date'] = $this->home_country_return_date;

        $declarationData['isolation_addresses'] = [];

        /** @var IsolationAddress $isolationAddress */
        foreach ($this->isolationaddresses()->get() as $isolationAddress) {
            $declarationData['isolation_addresses'][] = $isolationAddress->toArray();
        }

        $declarationData['question_1_answer'] = $this->question_1_answer;
        $declarationData['question_2_answer'] = $this->question_2_answer;
        $declarationData['question_3_answer'] = $this->question_3_answer;

        $declarationData['symptom_fever'] = (bool)$this->symptom_fever;
        $declarationData['symptom_swallow'] = (bool)$this->symptom_swallow;
        $declarationData['symptom_breathing'] = (bool)$this->symptom_breathing;
        $declarationData['symptom_cough'] = (bool)$this->symptom_cough;

        $declarationData['itinerary_country_list'] = [];

        /** @var ItineraryCountry $itineraryCountry */
        foreach ($this->itinerarycountries()->get() as $itineraryCountry) {
            $declarationData['itinerary_country_list'][] = $itineraryCountry->country_code;
        }

        $declarationData['vehicle_type'] = $this->vehicle_type;
        $declarationData['vehicle_registration_no'] = $this->vehicle_registration_no;

        $declarationData['created_at'] = $this->created_at;

        return $declarationData;
    }
}
