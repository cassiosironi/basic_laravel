<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

trait ReordersRecords
{
    /**
     * Aplica uma nova ordenação persistente a qualquer tabela.
     *
     * @param string $table       Ex: 'banners'
     * @param array  $orderedIds  Ex: [5, 2, 7, 1]
     * @param string $idColumn    Ex: 'id'
     * @param string $orderColumn Ex: 'ordem'
     * @param int    $start       Ex: 1 (ordem começa em 1)
     */
    protected function applyOrder(
        string $table,
        array $orderedIds,
        string $idColumn = 'id',
        string $orderColumn = 'ordem',
        int $start = 1
    ): int {
        $orderedIds = array_values(array_filter($orderedIds, fn($v) => is_numeric($v)));

        if (count($orderedIds) === 0) {
            return 0;
        }

        DB::beginTransaction();
        try {
            $pos = $start;
            $affectedTotal = 0;

            foreach ($orderedIds as $id) {
                $affected = DB::update(
                    "UPDATE {$table} SET {$orderColumn} = ? WHERE {$idColumn} = ?",
                    [$pos, (int)$id]
                );
                $affectedTotal += (int)$affected;
                $pos++;
            }

            DB::commit();
            return $affectedTotal;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}