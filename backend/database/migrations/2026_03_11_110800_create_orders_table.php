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
            $table->id();
            $table->string('order_number', 30)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('promo_code_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['PENDING', 'PAID', 'DELIVERED', 'CANCELED'])->default('PENDING');
            $table->enum('payment_method', ['CARD', 'CRYPTO', 'BANK_TRANSFER', 'CASH_ON_DELIVERY']);
            $table->enum('shipping_method', ['EMAIL', 'PICKUP', 'COURIER', 'ALZABOX', 'POST_OFFICE', 'PACKETA'])->nullable();
            $table->char('currency', 3)->default('EUR');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_total', 10, 2)->default(0);
            $table->decimal('shipping_total', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);
            $table->string('customer_full_name', 120);
            $table->string('customer_email', 120);
            $table->string('customer_phone', 40);
            $table->string('country', 120);
            $table->string('city', 120);
            $table->string('address', 200);
            $table->string('zip_code', 20);
            $table->text('notes')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'created_at']);
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

