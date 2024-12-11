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
        if(! Schema::hasTable('expense_details')) {
            Schema::create('expense_details', function (Blueprint $table) {
                $table->id();
                $table->date('trnx_date');
                $table->integer('expense_id');
                $table->integer('user_id');
                $table->integer('account_id');
                $table->float('amount', 14, 2);
                $table->string('title')->nullable();
                $table->string('details')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_details');
    }
};
