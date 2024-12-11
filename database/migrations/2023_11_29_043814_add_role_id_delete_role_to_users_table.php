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
        if(! Schema::hasColumns('users', ['role_id'])) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
                $table->unsignedBigInteger('role_id')->default(4); // add the role_id column
                $table->foreign('role_id')->references('id')->on('roles'); // add the foreign key constraint
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumns('users', ['role_id'])) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role');
                $table->dropColumn('role_id');
            });
        }
    }
};
