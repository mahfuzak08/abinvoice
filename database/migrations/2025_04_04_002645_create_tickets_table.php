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
        if(! Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table) {
                $table->id();
                $table->string('title')->comment('ticket title or subject');
                $table->string('description')->nullable();
                $table->json('imgs')->nullable();
                $table->string('priority')->default('Normal');
                $table->string('status')->default('Initialize');
                $table->timestamp('opening_date')->useCurrent();
                $table->timestamp('closing_date')->nullable();
                $table->unsignedBigInteger('submit_by');
                $table->unsignedBigInteger('client_id');
                $table->unsignedBigInteger('closing_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
