<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cicil_emas_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cicil_emas_transaction_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedSmallInteger('sequence');
            $table->date('due_date');
            $table->decimal('amount', 15, 2);
            $table->decimal('penalty_rate', 5, 2)->default(0);
            $table->decimal('penalty_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['cicil_emas_transaction_id', 'sequence'],
                'cicil_emas_tx_sequence_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cicil_emas_installments');
    }
};
