<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // relasi
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // ukuran
            $table->string('size')->nullable();

            // harga
            $table->decimal('price', 12, 2);

            // jumlah
            $table->integer('quantity')->default(1);

            // checkbox
            $table->boolean('is_selected')->default(true);

            $table->timestamps();

            $table->unique(['user_id', 'product_id', 'size']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
