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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id('idPurchase');
            $table->date('purchaseDate');
            $table->decimal('iva', 10, 2);
            $table->decimal('shipping_cost', 10, 2);
            $table->text('notes')->nullable();
            $table->string('paymentMethod')->nullable();
            $table->decimal('totalAmount', 10, 2);
            $table->string('invoiceNumber')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
