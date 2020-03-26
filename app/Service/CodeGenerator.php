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
        $bytes = bin2hex(random_bytes($length * 2));

        return strtoupper(substr($bytes, 0, $length));
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
        return 666666; // TODO: only for testing purposes!!!
//        return random_int(100000, 999999); // TODO: uncomment me!!!
    }
}
