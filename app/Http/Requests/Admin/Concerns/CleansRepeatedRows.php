<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Concerns;

/**
 * Repeated rows (counters, cards, menu items) arrive with empty rows left in
 * the form; keep only the filled ones, with just the known keys, in order.
 */
trait CleansRepeatedRows
{
    /**
     * @param  array<int|string, mixed>  $rows
     * @param  list<string>  $keys
     * @return list<array<string, string>>
     */
    protected function filledRows(array $rows, string $requiredKey, array $keys): array
    {
        $clean = [];

        foreach ($rows as $row) {
            if (! is_array($row) || blank($row[$requiredKey] ?? null)) {
                continue;
            }

            $clean[] = array_map(static fn (string $key): string => (string) ($row[$key] ?? ''), array_combine($keys, $keys));
        }

        return $clean;
    }
}
