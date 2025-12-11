<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('conversations')) {
            Schema::table('conversations', function (Blueprint $table) {
                // Drop existing foreign key if present
                try { $table->dropForeign(['creator_id']); } catch (\Throwable $e) {}
            });
            Schema::table('conversations', function (Blueprint $table) {
                // Make creator_id nullable and re-add FK with SET NULL
                try { $table->unsignedBigInteger('creator_id')->nullable()->change(); } catch (\Throwable $e) {}
                $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('conversations')) {
            Schema::table('conversations', function (Blueprint $table) {
                try { $table->dropForeign(['creator_id']); } catch (\Throwable $e) {}
                // You may revert to NOT NULL if needed; leaving as nullable for safety
            });
        }
    }
};

