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
        if(! Schema::hasColumns('purchases', ['payment', 'discount'])) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->json('payment')->nullable();
                $table->float('discount', 14, 2)->nullable();
                $table->dropColumn('discount_code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
