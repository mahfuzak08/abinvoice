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
        if(! Schema::hasColumns('customers', ['contact_id'])) {
            Schema::table('customers', function (Blueprint $table) {
                $table->unsignedBigInteger('contact_id')->nullable();
                $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('contact_id');
        });
    }
};
