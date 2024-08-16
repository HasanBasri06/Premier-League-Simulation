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
        Schema::create('team_powers', function (Blueprint $table) {
            $table->id();
            $table->integer('PTS')->nullable();
            $table->integer('P')->nullable();
            $table->integer('W')->nullable();
            $table->integer('D')->nullable();
            $table->integer('L')->nullable();
            $table->integer('GD')->nullable();
            $table->integer('team_id')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_power');
    }
};
