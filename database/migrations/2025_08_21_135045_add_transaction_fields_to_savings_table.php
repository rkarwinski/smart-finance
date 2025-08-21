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
        Schema::table('savings', function (Blueprint $table) {
            $table->enum('transaction_type', ['deposit', 'withdrawal'])->default('deposit')->after('currency');
            $table->text('reason')->nullable()->after('transaction_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savings', function (Blueprint $table) {
            $table->dropColumn(['transaction_type', 'reason']);
        });
    }
};
