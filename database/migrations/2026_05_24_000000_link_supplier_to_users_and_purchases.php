<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable()->after('is_superuser');
            $table->unique('supplier_id');
            $table->foreign('supplier_id')
                ->references('idSupplier')->on('suppliers')
                ->nullOnDelete();
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('idSupplier')->nullable()->after('idPurchase');
            $table->foreign('idSupplier')
                ->references('idSupplier')->on('suppliers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['idSupplier']);
            $table->dropColumn('idSupplier');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropUnique(['supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }
};
