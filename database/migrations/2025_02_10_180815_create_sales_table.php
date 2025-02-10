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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->decimal('tax', 15, 2)->default(0);
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue']);
            $table->date('invoice_date');
            $table->date('due_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
