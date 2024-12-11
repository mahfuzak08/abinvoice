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
        if(! Schema::hasColumns('sales', ['total_due'])) {
            Schema::table('sales', function (Blueprint $table) {
                $table->float('total_due', 14, 2)->default(0)->after('total');
                $table->dropColumn('asof_date_due');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->float('asof_date_due', 14, 2)->default(0)->after('total');
            $table->dropColumn('total_due');
        });
    }
};
