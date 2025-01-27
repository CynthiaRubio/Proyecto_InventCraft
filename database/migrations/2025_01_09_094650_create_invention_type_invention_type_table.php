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
        Schema::create('invention_type_invention_types', function (Blueprint $table) {
            $table->foreignId('invention_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('invention_type_need_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
            $table->primary(['invention_type_id', 'invention_type_need_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invention_inventions');
        Schema::dropIfExists('invention_type_invention_types');
    }
};
