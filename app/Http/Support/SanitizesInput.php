<?php

namespace App\Support;

trait SanitizesInput
{
    /**
     * Limpa string contra XSS (HTML/JS)
     * Ideal para títulos, textos institucionais, labels etc.
     */
    protected function clean(?string $value): string
    {
        return htmlspecialchars(
            trim((string) $value),
            ENT_QUOTES,
            'UTF-8'
        );
    }

    /**
     * Limpa um array de campos específicos do request
     * Retorna apenas os campos saneados
     */
    protected function cleanOnly(array $fields, array $source): array
    {
        $cleaned = [];

        foreach ($fields as $field) {
            $cleaned[$field] = array_key_exists($field, $source)
                ? $this->clean($source[$field])
                : null;
        }

        return $cleaned;
    }
}