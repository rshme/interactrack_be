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
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->enum('type', ['email', 'call', 'meeting'])->default('email');
            $table->string('subject');
            $table->text('description');
            $table->datetime('interaction_date');
            $table->enum('status', ['planned', 'completed', 'cancelled', 'follow-up-required'])->default('planned');
            $table->text('outcome')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
