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
        if ( ! Schema::hasTable('account_tranxes')){
            Schema::create('account_tranxes', function (Blueprint $table) {
                $table->id();
                $table->integer('account_id');
                $table->date('tranx_date');
                $table->integer('ref_id')->default(0);
                $table->string('ref_type')->nullable();
                $table->float('amount', 14, 2)->default(0);
                $table->integer('user_id');
                $table->string('note')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_tranxes');
    }
};
