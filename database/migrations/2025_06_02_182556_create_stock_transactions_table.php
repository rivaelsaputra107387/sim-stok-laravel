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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['in', 'out']); // 'in' (masuk), 'out' (keluar)
            $table->integer('quantity');

            // REVISED: Price fields only for stock IN transactions
            $table->decimal('total_price', 15, 2)->nullable(); // Only for 'in' transactions

            // NEW: Expired date for each batch (only for stock IN)
            $table->date('expired_date')->nullable(); // Only for 'in' transactions
            // $table->string('batch_number')->nullable(); // Optional batch tracking

            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->text('notes')->nullable();
            $table->date('transaction_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
