<?php

use App\Domains\Pledges\Enums\PledgeRecursEnum;
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
        Schema::create('pledges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('donor_id')->references('id')->on('donors');
            $table->unsignedBigInteger('amount')->nullable();
            $table->enum('recurs', PledgeRecursEnum::getValues());
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pledges');
    }
};
