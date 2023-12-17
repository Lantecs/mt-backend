<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_budgets', function (Blueprint $table) {
            $table->id('budget_id');
            $table->unsignedBigInteger('user_id');
            $table->string('budget_type');
            $table->string('category');
            $table->decimal('amount');
            $table->date('date');
            $table->timestamps();
            // $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('user_budgets', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_budgets');
    }
};
