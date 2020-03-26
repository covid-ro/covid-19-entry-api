<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DeclarationCode
 * @package App
 *
 * @property int $id
 * @property string $code
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class DeclarationCode extends Model
{
    use SoftDeletes;
}
