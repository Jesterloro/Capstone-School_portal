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
            // Add a status column to the subjects table
            $table->string('status')->default('regular'); // 'regular' is the default value, you can change this to suit your needs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Drop the status column if the migration is rolled back
            $table->dropColumn('status');
        });
    }
};
