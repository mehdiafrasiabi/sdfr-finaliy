<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Some imported databases lost primary-key/auto-increment metadata from
        // every table, including Laravel's own migrations table. Repair that
        // table first so this migration can be recorded after it runs.
        $this->repairAutoIncrementingId('migrations');
        $this->repairAutoIncrementingId('trial_acquisition_calls');
    }

    private function repairAutoIncrementingId(string $table): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'id')) {
            return;
        }

        $quotedTable = '`'.str_replace('`', '``', $table).'`';
        $idColumn = collect(DB::select("SHOW COLUMNS FROM {$quotedTable}"))
            ->first(fn (object $column): bool => $column->Field === 'id');

        if (! $idColumn) {
            return;
        }

        $indexes = collect(DB::select("SHOW INDEX FROM {$quotedTable}"));
        $primaryColumns = $indexes
            ->where('Key_name', 'PRIMARY')
            ->sortBy('Seq_in_index')
            ->pluck('Column_name');

        if ($primaryColumns->isEmpty()) {
            $duplicateId = DB::table($table)
                ->select('id')
                ->groupBy('id')
                ->havingRaw('COUNT(*) > 1')
                ->value('id');

            if ($duplicateId !== null) {
                throw new \RuntimeException(
                    "Cannot repair {$table}.id because duplicate id {$duplicateId} exists."
                );
            }

            DB::statement("ALTER TABLE {$quotedTable} ADD PRIMARY KEY (`id`)");
        } elseif (! $primaryColumns->contains('id')) {
            throw new \RuntimeException(
                "Cannot repair {$table}.id because the table has a primary key on another column."
            );
        }

        if (! str_contains(strtolower((string) ($idColumn->Extra ?? '')), 'auto_increment')) {
            $columnType = (string) $idColumn->Type;

            if (! preg_match('/^[a-z]+(?:\(\d+(?:,\d+)?\))?(?: unsigned)?$/i', $columnType)) {
                throw new \RuntimeException("Cannot safely determine the column type for {$table}.id.");
            }

            DB::statement(
                "ALTER TABLE {$quotedTable} MODIFY `id` {$columnType} NOT NULL AUTO_INCREMENT"
            );
        }
    }

    public function down(): void
    {
        // This migration repairs a broken database invariant. Reverting it would
        // make new call records fail again, so the repair is intentionally kept.
    }
};
