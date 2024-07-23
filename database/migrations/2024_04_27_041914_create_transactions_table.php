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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->unsignedBigInteger('amount');
            $table->uuid('author_id')->references('id')->on('users');
            
            $table->string('fromable_type'); // RevenueStream, Donor or Account
            $table->uuid('fromable_id');

            $table->string('toable_type'); // Employee, Vendor or Account
            $table->uuid('toable_id');

            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
