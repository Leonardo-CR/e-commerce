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
        Schema::create('earphones', function (Blueprint $table) {
            $table->id('idEarphone');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('idSupplier');
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->foreign('idSupplier')->references('idSupplier')->on('suppliers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('earphones');
    }
};
