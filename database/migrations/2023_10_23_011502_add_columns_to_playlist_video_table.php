<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('playlist_video', function (Blueprint $table) {
            $table->string('title');
            $table->string('channel');
            $table->string('thumbnails');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('playlist_video', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('channel');
            $table->dropColumn('thumbnails');
        });
    }
};
