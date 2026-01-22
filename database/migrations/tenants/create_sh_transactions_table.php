<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Noardcode\LaravelSignhost\Enums\TransactionType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('sh_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->enum('type', [TransactionType::Unknown->value, TransactionType::IdProof->value, TransactionType::DocumentSign->value])
                ->default(TransactionType::Unknown->value);
            $table->unsignedInteger('status');

            $table->boolean('seal')->default(false);
            $table->string('reference')->nullable();
            $table->string('postback_url')->nullable();
            $table->unsignedInteger('sign_request_mode')->nullable();
            $table->unsignedInteger('days_to_expire')->nullable();
            $table->boolean('send_email_notifications')->default(false);
            $table->dateTime('created_date_time')->nullable();
            $table->dateTime('modified_date_time')->nullable();
            $table->dateTime('canceled_date_time')->nullable();
            $table->boolean('authenticated')->default(false);
            $table->unsignedInteger('probability')->default(0);
            $table->longText('context')->nullable();
            $table->string('receipt')->nullable();
            $table->longText('object')->nullable();
            $table->longText('webhook_response')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('sh_transactions');
    }
};
