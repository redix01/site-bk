<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const NEW_TYPES = [
        'deposit',
        'withdrawal',
        'transfer',
        'fee',
        'refund',
        'stamp_duty',
        'monthly_fee',
        'general',
    ];

    private const OLD_TYPES = [
        'deposit',
        'withdrawal',
        'transfer',
        'fee',
        'refund',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::statement('ALTER TABLE transactions MODIFY type ' . $this->enumDefinition(self::NEW_TYPES));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::table('transactions')
            ->whereIn('type', ['stamp_duty', 'monthly_fee', 'general'])
            ->update(['type' => 'fee']);

        DB::statement('ALTER TABLE transactions MODIFY type ' . $this->enumDefinition(self::OLD_TYPES));
    }

    private function enumDefinition(array $types): string
    {
        $values = collect($types)
            ->map(fn (string $type) => "'" . str_replace("'", "''", $type) . "'")
            ->implode(',');

        return "ENUM({$values}) NOT NULL";
    }
};
