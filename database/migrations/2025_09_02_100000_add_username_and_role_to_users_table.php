<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 100)->nullable()->after('name');
            $table->string('role', 50)->default('user')->after('email');
        });

        DB::table('users')
            ->orderBy('id')
            ->select('id', 'name')
            ->lazyById()
            ->each(function ($user) {
                $baseUsername = Str::slug($user->name ?? '', '_');

                if ($baseUsername === '') {
                    $baseUsername = 'user';
                }

                $baseUsername = Str::substr($baseUsername, 0, 90);
                $username = trim($baseUsername . '_' . $user->id, '_');

                DB::table('users')->where('id', $user->id)->update([
                    'username' => $username,
                ]);
            });

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE users MODIFY username VARCHAR(100) NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN username SET NOT NULL');
        } else {
            throw new RuntimeException("Unsupported database driver [{$driver}] for setting username column as NOT NULL.");
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unique('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_username_unique');
            $table->dropColumn(['username', 'role']);
        });
    }
};
