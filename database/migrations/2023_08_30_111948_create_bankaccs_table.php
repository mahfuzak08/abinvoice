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
        if ( ! Schema::hasTable('bankaccs')){
            Schema::create('bankaccs', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->defautl('Cash')->comment('Cash/ Due/ Others');
                $table->string('bank_name')->nullable();
                $table->string('bank_address')->nullable();
                $table->string('acc_no')->nullable();
                $table->string('currency')->defautl('BDT');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bankaccs');
    }
};
