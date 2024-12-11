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
        if ( ! Schema::hasTable('purchases')){
            Schema::create('purchases', function (Blueprint $table) {
                $table->id();
                $table->string('order_id');
                $table->string('order_type')->default('purchase');
                $table->integer('user_id');
                $table->integer('vendor_id');
                $table->json('products')->nullable();
                $table->json('return_items')->nullable();
                $table->date('date');
                $table->integer('status')->default(1);
                $table->string('discount_code')->nullable();
                $table->float('shipping_cost', 14, 2)->default(0);
                $table->float('labour_cost', 14, 2)->default(0);
                $table->float('carrying_cost', 14, 2)->default(0);
                $table->float('other_cost', 14, 2)->default(0);
                $table->float('total', 14, 2)->default(0);
                $table->float('asof_date_due', 14, 2)->default(0);
                $table->float('note', 14, 2)->default(0);
                $table->timestamps();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
