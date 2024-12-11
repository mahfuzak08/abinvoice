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
        if(! Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
                $table->id();
                $table->date('date');
                $table->unsignedBigInteger('emp_id');
                $table->integer('hours')->default(0);
                $table->integer('user_id');
                $table->timestamps();
    
                $table->unique(['date', 'emp_id']);
    
                // Foreign key constraint for emp_id
                $table->foreign('emp_id')->references('id')->on('employees')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
