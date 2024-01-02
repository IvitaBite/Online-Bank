<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cryptocurrencies', function (Blueprint $table) {
            $table->string('pair')->primary();
            $table->string('symbol');
            $table->string('name');
            $table->string('currency_symbol');
            $table->foreign('currency_symbol')
                ->references('symbol')
                ->on('currencies')
                ->onDelete('cascade');
            $table->decimal('buy_rate', 30, 15);
            $table->decimal('sell_rate', 30, 15);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->dropForeignKeys();
        $this->dropAccountsTable();
    }

    private function dropForeignKeys(): void
    {
        Schema::table('cryptocurrencies', function (Blueprint $table) {
            $table->dropForeign(['currency_symbol']);
        });
    }

    private function dropAccountsTable(): void
    {
        Schema::dropIfExists('cryptocurrencies');
    }
};
