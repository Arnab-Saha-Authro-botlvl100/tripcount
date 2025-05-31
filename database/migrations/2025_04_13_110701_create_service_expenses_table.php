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
        Schema::create('service_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_service_id');
            $table->unsignedBigInteger('user');
            $table->string('expense_title');
            $table->decimal('expense_amount', 12, 2);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_expenses');
    }
};
