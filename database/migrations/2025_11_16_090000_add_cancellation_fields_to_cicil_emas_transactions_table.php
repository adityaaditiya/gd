<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('cicil_emas_transactions', 'status')) {
                $table->string('status', 32)
                    ->default('active')
                    ->after('option_label');
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'cancelled_at')) {
                $table->timestamp('cancelled_at')
                    ->nullable()
                    ->after('status');
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'cancelled_by')) {
                $table->foreignId('cancelled_by')
                    ->nullable()
                    ->after('cancelled_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'cancellation_reason')) {
                $table->text('cancellation_reason')
                    ->nullable()
                    ->after('cancelled_by');
            }
        });

        DB::table('cicil_emas_transactions')
            ->whereNull('status')
            ->update(['status' => 'active']);
    }

    public function down(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('cicil_emas_transactions', 'cancellation_reason')) {
                $table->dropColumn('cancellation_reason');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'cancelled_by')) {
                $table->dropForeign(['cancelled_by']);
                $table->dropColumn('cancelled_by');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'cancelled_at')) {
                $table->dropColumn('cancelled_at');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
