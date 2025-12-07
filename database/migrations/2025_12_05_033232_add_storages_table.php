<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    
public function up(): void
{
    Schema::table('storages', function (Blueprint $table) {
        $table->integer('width')->nullable()->after('name');
        $table->integer('height')->nullable()->after('width');
        $table->integer('depth')->nullable()->after('height');
    });
}

public function down(): void
{
    Schema::table('storages', function (Blueprint $table) {
        $table->dropColumn(['width', 'height', 'depth']);
    });
}



};
