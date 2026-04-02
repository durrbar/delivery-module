<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Delivery\Enums\DeliveryStatus;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('order_id')->constrained()->cascadeOnDelete(); // Link to Order
            $table->enum(
                'status',
                DeliveryStatus::cases()
            )->default(DeliveryStatus::Pending->value); // e.g., pending, in transit, delivered
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
