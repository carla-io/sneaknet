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
        Schema::table('products', function (Blueprint $table) {
            // Drop the existing columns
            $table->dropColumn('quantity');
            $table->dropColumn('description');

            // Add new columns
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Reverse the changes made in the up() method
            $table->integer('quantity');
            $table->text('description')->nullable();

            // Drop the newly added columns
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->dropColumn('image');
        });
    }
};
