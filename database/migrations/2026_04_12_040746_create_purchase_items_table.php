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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id('idPurchase_Item');
            $table->unsignedBigInteger('idPurchase');
            $table->unsignedBigInteger('idEarphone');
            $table->integer('quantity');
            $table->decimal('unit_cost', 10, 2);
            $table->date('received_date')->nullable();
            $table->foreign('idPurchase')->references('idPurchase')->on('purchases')->onDelete('cascade');
            $table->foreign('idEarphone')->references('idEarphone')->on('earphones')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
