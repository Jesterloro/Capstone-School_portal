<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->integer('current_semester')->default(1); // 1 or 2
        $table->timestamps();
    });

    // Insert a default semester
    DB::table('settings')->insert(['current_semester' => 1]);
}

public function down()
{
    Schema::dropIfExists('settings');
}

};
