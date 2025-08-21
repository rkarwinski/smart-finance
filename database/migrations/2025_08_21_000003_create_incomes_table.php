<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3); // BRL or EUR
            $table->decimal('amount_eur', 15, 2)->nullable(); // Converted if BRL
            $table->text('note')->nullable();
            $table->string('category'); // salary, investments, others
            $table->date('date')->default(DB::raw('CURRENT_DATE'));
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
