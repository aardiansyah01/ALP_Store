<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete()
                ->unique(); // ⬅️ hanya sekali per order

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('reason');
            $table->text('description');

            $table->string('receipt_image');
            $table->string('unboxing_video');

            $table->enum('status', [
                'requested',
                'approved',
                'rejected'
            ])->default('requested');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_returns');
    }
};
