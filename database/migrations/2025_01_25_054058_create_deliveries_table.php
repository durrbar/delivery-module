<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('order_id')->references('id')->on('orders')->cascadeOnDelete(); // Link to Order
            $table->enum('status', ['pending', 'in_transit', 'delivered', 'failed'])->default('pending'); // e.g., pending, in transit, delivered
            $table->string('tracking_number')->nullable();
            $table->string('delivery_provider');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
