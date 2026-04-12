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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('idOrder');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('idPayment')->nullable();
            $table->decimal('shippingCost', 10, 2);
            $table->decimal('totalAmount', 10, 2);
            $table->string('shippingCompany')->nullable();
            $table->string('TrackingNumber')->nullable();
            $table->string('status');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idPayment')->references('idPayment')->on('payments')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
