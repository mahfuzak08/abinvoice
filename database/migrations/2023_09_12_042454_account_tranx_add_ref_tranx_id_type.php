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
        if(! Schema::hasColumns('account_tranxes', ['ref_tranx_id', 'ref_tranx_type'])) {
            Schema::table('account_tranxes', function (Blueprint $table) {
                $table->string('ref_tranx_id')->default(0);
                $table->string('ref_tranx_type')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_tranxes', function (Blueprint $table) {
            $table->dropColumn('ref_tranx_id');
            $table->dropColumn('ref_tranx_type');
        });
    }
};
