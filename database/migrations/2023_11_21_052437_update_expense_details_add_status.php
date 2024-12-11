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
        if(! Schema::hasColumns('expense_details', ['status'])) {
            Schema::table('expense_details', function (Blueprint $table) {
                $table->integer('status')->default(1)->after('amount');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_details', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
