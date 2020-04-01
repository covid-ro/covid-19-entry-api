<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DeclarationSignature
 * @package App
 *
 * @property int $id
 * @property int $declaration_id
 * @property string $image
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class DeclarationSignature extends Model
{
    use SoftDeletes;

    /**
     * @return BelongsTo
     */
    public function declaration()
    {
        return $this->belongsTo(Declaration::class);
    }
}
