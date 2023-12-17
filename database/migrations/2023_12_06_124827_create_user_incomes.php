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
        Schema::create('user_incomes', function (Blueprint $table) {
            $table->id('income_id');
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->decimal('amount');
            $table->date('date');
            $table->timestamps();
        });

        Schema::table('user_incomes', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_incomes');
    }
};
