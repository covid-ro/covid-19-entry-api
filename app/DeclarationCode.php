<?php

namespace App;

use App\Service\CodeGenerator;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;

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

    /**
     * @return static
     */
    public static function generateDeclarationCode(): self
    {
        $declarationCode = new self();
        $declarationCode->code = (new CodeGenerator())->generateDeclarationCode(7);

        try {
            $declarationCode->save();
        } catch (QueryException $exception) {
            return self::generateDeclarationCode(); // retry
        }

        return $declarationCode;
    }
}
