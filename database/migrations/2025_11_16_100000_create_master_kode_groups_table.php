<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_kode_groups', function (Blueprint $table) {
            $table->id();
            $table->string('kode_group')->unique();
            $table->decimal('harga', 16, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_kode_groups');
    }
};
