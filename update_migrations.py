import os
import re

dir_path = 'database/migrations'

schemas = {
    'users': """Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('CURP')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_superuser')->default(false);
            $table->timestamp('last_login')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });""",
    'addresses': """Schema::create('addresses', function (Blueprint $table) {
            $table->id('idAddress');
            $table->string('street');
            $table->string('colony');
            $table->string('city');
            $table->boolean('eliminated')->default(false);
            $table->string('number');
            $table->string('state');
            $table->string('zip');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });""",
    'suppliers': """Schema::create('suppliers', function (Blueprint $table) {
            $table->id('idSupplier');
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('email');
            $table->timestamps();
        });""",
    'earphones': """Schema::create('earphones', function (Blueprint $table) {
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
        });""",
    'carts': """Schema::create('carts', function (Blueprint $table) {
            $table->id('idCart');
            $table->string('status');
            $table->timestamp('expressAt')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });""",
    'cart_items': """Schema::create('cart_items', function (Blueprint $table) {
            $table->id('idCart_Item');
            $table->unsignedBigInteger('idEarphone');
            $table->unsignedBigInteger('idCart');
            $table->decimal('subtotal', 10, 2);
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->foreign('idEarphone')->references('idEarphone')->on('earphones')->onDelete('cascade');
            $table->foreign('idCart')->references('idCart')->on('carts')->onDelete('cascade');
            $table->timestamps();
        });""",
    'reviews': """Schema::create('reviews', function (Blueprint $table) {
            $table->id('idReview');
            $table->text('comment');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('idEarphone');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idEarphone')->references('idEarphone')->on('earphones')->onDelete('cascade');
            $table->timestamps();
        });""",
    'payments': """Schema::create('payments', function (Blueprint $table) {
            $table->id('idPayment');
            $table->timestamp('payment_date');
            $table->decimal('amount', 10, 2);
            $table->string('status');
            $table->string('method');
            $table->timestamps();
        });""",
    'orders': """Schema::create('orders', function (Blueprint $table) {
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
        });""",
    'order_items': """Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->unsignedBigInteger('idOrder');
            $table->unsignedBigInteger('idEarphone');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->foreign('idOrder')->references('idOrder')->on('orders')->onDelete('cascade');
            $table->foreign('idEarphone')->references('idEarphone')->on('earphones')->onDelete('cascade');
            $table->timestamps();
        });""",
    'refunds': """Schema::create('refunds', function (Blueprint $table) {
            $table->id('idRefund');
            $table->text('reason');
            $table->string('status');
            $table->unsignedBigInteger('idOrder');
            $table->foreign('idOrder')->references('idOrder')->on('orders')->onDelete('cascade');
            $table->timestamps();
        });""",
    'purchases': """Schema::create('purchases', function (Blueprint $table) {
            $table->id('idPurchase');
            $table->date('purchaseDate');
            $table->decimal('iva', 10, 2);
            $table->decimal('shipping_cost', 10, 2);
            $table->text('notes')->nullable();
            $table->string('paymentMethod')->nullable();
            $table->decimal('totalAmount', 10, 2);
            $table->string('invoiceNumber')->nullable();
            $table->timestamps();
        });""",
    'purchase_items': """Schema::create('purchase_items', function (Blueprint $table) {
            $table->id('idPurchase_Item');
            $table->unsignedBigInteger('idPurchase');
            $table->unsignedBigInteger('idEarphone');
            $table->integer('quantity');
            $table->decimal('unit_cost', 10, 2);
            $table->date('received_date')->nullable();
            $table->foreign('idPurchase')->references('idPurchase')->on('purchases')->onDelete('cascade');
            $table->foreign('idEarphone')->references('idEarphone')->on('earphones')->onDelete('cascade');
            $table->timestamps();
        });"""
}

for filename in os.listdir(dir_path):
    if filename.endswith('.php'):
        filepath = os.path.join(dir_path, filename)
        with open(filepath, 'r') as f:
            content = f.read()
            
        for table, schema_code in schemas.items():
            if f'create_{table}_table' in filename:
                # Replace the entire Schema::create block up to its closing brace and semicolon
                pattern = f"Schema::create\\('{table}', function \\(Blueprint \\$table\\) \\{{.*?\\}}\\);"
                content = re.sub(pattern, schema_code, content, flags=re.DOTALL)
                with open(filepath, 'w') as f:
                    f.write(content)
                print(f"Updated {filename} for table {table}")
