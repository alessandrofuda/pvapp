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
        Schema::table('areas', function (Blueprint $table) {
            $table->index('region_id');
            $table->index('province_id');
        });

        Schema::create('operator_areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operator_id');
            $table->unsignedInteger('region_id')->nullable();
            $table->unsignedInteger('province_id')->nullable();
            $table->timestamps();

            $table->foreign('operator_id')->references('id')->on('operators')->onDelete('cascade');
            $table->foreign('region_id')->references('region_id')->on('areas'); // ->onDelete('cascade');
            $table->foreign('province_id')->references('province_id')->on('areas'); // ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operator_areas');

        Schema::table('areas', function(Blueprint $table) {
            $table->dropIndex(['region_id']);
            $table->dropIndex(['province_id']);
        });
    }
};
