<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Delivery\Enums\ShippingType;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_classes', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->double('amount');
            $table->string('is_global')->default(true);
            $table->enum('type', ShippingType::getValues())->default(ShippingType::FIXED);
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table): void {
            // $table->uuid('shipping_class_id')->nullable();
            $table->foreignUuid('shipping_class_id')->references('id')->on('shipping_classes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->dropForeign(['shipping_class_id']);
                $table->dropColumn('shipping_class_id');
            });
        }
        
        Schema::dropIfExists('shipping_classes');
    }
};
