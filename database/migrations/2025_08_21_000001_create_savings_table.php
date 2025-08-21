<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('savings', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3); // BRL or EUR
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('savings');
    }
};
