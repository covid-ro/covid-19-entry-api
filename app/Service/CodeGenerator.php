<?php

namespace App\Service;

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
    public function generateCode($length = 6): string
    {
        $bytes = bin2hex(random_bytes($length * 2));

        return strtoupper(substr($bytes, 0, $length));
    }
}
