<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table) {

            // user_id は既にあるので追加しない

            if (!Schema::hasColumn('favorites', 'storage_id')) {
                $table->foreignId('storage_id')
                      ->constrained('storages')
                      ->cascadeOnDelete();
            }

        });
    }

    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {

            if (Schema::hasColumn('favorites', 'storage_id')) {
                $table->dropForeign(['storage_id']);
                $table->dropColumn('storage_id');
            }

        });
    }
};
