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
        Schema::create('sh_transaction_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id')
                ->constrained('sh_transactions')
                ->cascadeOnDelete();
            $table->foreignUuid('transaction_signer_id')
                ->nullable()
                ->constrained('sh_transaction_receivers');
            $table->string('state')->nullable();
            $table->unsignedInteger('state_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sh_transaction_activities');
    }
};
