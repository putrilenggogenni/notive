<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->boolean('is_favorite')->default(false)->after('photo');
            $table->unsignedInteger('word_count')->default(0)->after('is_favorite');
        });
    }

    public function down(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn(['is_favorite', 'word_count']);
        });
    }
};