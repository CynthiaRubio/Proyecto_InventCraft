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
        Schema::create('inventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invention_type_id')->constrained('invention_types')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->foreignId('action_building_id')->constrained('action_buildings')->onDelete('cascade')->nullable();
            $table->foreignId('invention_created_id')->constrained('inventions')->onDelete('cascade')->nullable();
            $table->string('name');
            $table->float('efficiency');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventions');
    }
};
