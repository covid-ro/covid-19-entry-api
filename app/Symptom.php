<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Symptom
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property DateTime| $created_at
 * @property DateTime| $updated_at
 */
class Symptom extends Model
{
    /**
     * @return BelongsToMany
     */
    public function declarations()
    {
        return $this->belongsToMany(Declaration::class);
    }
}
