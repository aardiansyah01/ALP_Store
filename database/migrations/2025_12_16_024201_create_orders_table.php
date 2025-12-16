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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // data penerima
            $table->string('receiver_name');

            // alamat lengkap
            $table->text('address');
            $table->string('city');
            $table->string('village');
            $table->string('dusun')->nullable();
            $table->string('rt');
            $table->string('rw');

            // Pengiriman n pembayaran
            $table->string('shipping_service');
            $table->integer('shipping_cost');
            $table->string('payment_method');

            // Total
            $table->integer('subtotal');
            $table->integer('total');

            // Status order
            $table->enum('status', ['pending', 'paid', 'shipped', 'completed'])
                ->default('pending');

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
