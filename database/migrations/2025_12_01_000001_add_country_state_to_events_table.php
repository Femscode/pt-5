<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'country')) {
                $table->string('country')->nullable()->index();
            }
            if (!Schema::hasColumn('events', 'state')) {
                $table->string('state')->nullable()->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'country')) {
                $table->dropColumn('country');
            }
            if (Schema::hasColumn('events', 'state')) {
                $table->dropColumn('state');
            }
        });
    }
};

