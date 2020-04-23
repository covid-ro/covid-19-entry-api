<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DspDeclaration
 * @package App
 *
 * @property int $id
 * @property int $declaration_id
 * @property string $body
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class DspDeclaration extends Model
{
    /**
     * @return BelongsTo
     */
    public function declaration()
    {
        return $this->belongsTo(Declaration::class);
    }
}
