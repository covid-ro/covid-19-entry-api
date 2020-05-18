<?php

namespace App\Service;

use Illuminate\Support\Str;

/**
 * Class CodeGenerator
 * @package App\Service
 */
class CodeGenerator
{
    /**
     * @param int $length
     * @return string
     */
    public function generateDeclarationCode($length = 6): string
    {
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= mt_rand(0, 9);
        }

        return $string;
    }

    /**
     * @param int $length
     * @return string
     */
    public function generateUserToken($length = 32): string
    {
        return Str::random($length);
    }

    /**
     * @return int
     */
    public function generateSmsCode(): int
    {
        return random_int(100000, 999999);
    }
}
