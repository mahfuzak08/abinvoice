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
        if(! Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('mobile')->unique();
                $table->string('gender')->default('Male');
                $table->string('address')->nullable();
                $table->string('nid')->nullable();
                $table->string('image')->nullable();
                $table->float('salary', 14, 2)->default(0);
                $table->float('bonus', 14, 2)->default(0);
                $table->string('emp_type')->default("Permanent");
                $table->date('joining')->nullable();
                $table->date('closing')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
