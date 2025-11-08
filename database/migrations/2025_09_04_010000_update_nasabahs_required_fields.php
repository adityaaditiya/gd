<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('nasabahs')) {
            return;
        }

        DB::table('nasabahs')->whereNull('kota')->update(['kota' => '']);
        DB::table('nasabahs')->whereNull('kelurahan')->update(['kelurahan' => '']);
        DB::table('nasabahs')->whereNull('kecamatan')->update(['kecamatan' => '']);

        DB::statement("ALTER TABLE nasabahs MODIFY kota VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE nasabahs MODIFY kelurahan VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE nasabahs MODIFY kecamatan VARCHAR(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('nasabahs')) {
            return;
        }

        DB::statement("ALTER TABLE nasabahs MODIFY kota VARCHAR(255) NULL");
        DB::statement("ALTER TABLE nasabahs MODIFY kelurahan VARCHAR(255) NULL");
        DB::statement("ALTER TABLE nasabahs MODIFY kecamatan VARCHAR(255) NULL");
    }
};
