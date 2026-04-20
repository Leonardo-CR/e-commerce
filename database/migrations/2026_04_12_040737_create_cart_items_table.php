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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('idCart_Item');
            $table->unsignedBigInteger('idEarphone');
            $table->unsignedBigInteger('idCart');
            $table->decimal('subtotal', 10, 2);
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->string('color')->nullable();
            $table->foreign('idEarphone')->references('idEarphone')->on('earphones')->onDelete('cascade');
            $table->foreign('idCart')->references('idCart')->on('carts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
