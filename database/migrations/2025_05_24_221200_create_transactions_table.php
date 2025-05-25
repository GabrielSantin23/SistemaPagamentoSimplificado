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
        Schema::create("transactions", function (Blueprint $table) {
            $table->id();
            $table->foreignId("payer_id")->constrained("users")->comment("ID do usuário que envia o dinheiro (pagador)");
            $table->foreignId("payee_id")->constrained("users")->comment("ID do usuário que recebe o dinheiro (beneficiário)");
            $table->decimal("value", 10, 2)->comment("Valor da transferência");
            // Status da transação: PENDING, AUTHORIZED, COMPLETED, FAILED, REVERSED
            $table->enum("status", ["PENDING", "AUTHORIZED", "COMPLETED", "FAILED", "REVERSED"])->default("PENDING");
            $table->timestamp("authorized_at")->nullable()->comment("Data e hora da autorização externa");
            $table->timestamp("completed_at")->nullable()->comment("Data e hora da conclusão da transferência");
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("transactions");
    }
};

