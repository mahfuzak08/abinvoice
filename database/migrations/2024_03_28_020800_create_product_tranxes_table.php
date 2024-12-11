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
        if(! Schema::hasTable('product_tranxes')) {
            Schema::create('product_tranxes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('variant_id');
                $table->string('from_inventory')->nullable();
                $table->unsignedBigInteger('order_id');
                $table->string('order_type');
                $table->date('date');
                $table->string('inout');
                $table->float('qty', 14, 2);
                $table->string('batch_no')->nullable();
                $table->date('expiry_date')->nullable();
                $table->float('actual_sell_price', 14, 2)->default(0);
                $table->float('actual_buy_price', 14, 2)->default(0);
                $table->timestamps();
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('variant_id')->references('id')->on('variants')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tranxes');
    }
};
