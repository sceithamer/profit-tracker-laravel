<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('permissions')->nullable()->after('email_verified_at');
            $table->boolean('is_admin')->default(false)->after('permissions');
            
            // SQLite optimization indexes
            $table->index('is_admin');
        });
        
        // Add JSON index for SQLite (if supported)
        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement('CREATE INDEX IF NOT EXISTS idx_users_permissions_json ON users (json_extract(permissions, \'$\'))');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_admin']);
            $table->dropColumn(['permissions', 'is_admin']);
        });
        
        // Drop JSON index for SQLite
        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement('DROP INDEX IF EXISTS idx_users_permissions_json');
        }
    }
};
