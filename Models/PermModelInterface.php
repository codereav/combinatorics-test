<?php

namespace App\Models;

/**
 * Interface PermModelInterface
 * @package App\Models
 */
interface PermModelInterface
{
    /**
     * @param string $requestMethod
     * @param int $fieldsCount
     * @param int $chipsCount
     * @return bool
     */
    public function validate(string $requestMethod, int $fieldsCount, int $chipsCount): bool;

    /**
     * @return \Generator
     */
    public function getData(): \Generator;


}