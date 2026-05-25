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
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reference')->unique();
            $table->enum('type', ['deposit', 'withdrawal', 'transfer', 'fee', 'refund', 'stamp_duty', 'monthly_fee', 'general']);
            $table->bigInteger('amount'); // in kobo (smallest currency unit)
            $table->bigInteger('fee')->default(0); // in kobo
            $table->string('status')->default('pending'); // pending, completed, failed, reversed
            $table->string('description')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('reversed_transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index(['type', 'status', 'created_at']);
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
