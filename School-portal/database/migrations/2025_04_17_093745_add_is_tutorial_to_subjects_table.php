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
        Schema::table('subjects', function (Blueprint $table) {
            // Add the 'is_tutorial' column to the 'subjects' table
            $table->boolean('is_tutorial')->default(false); // Set default as false
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Drop the 'is_tutorial' column if rollback is needed
            $table->dropColumn('is_tutorial');
        });
    }
};
