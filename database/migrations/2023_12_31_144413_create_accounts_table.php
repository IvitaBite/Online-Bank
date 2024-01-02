<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->string('account_number')->unique()->primary();
            $table->string('account_name');
            $table->enum('type', ['checking', 'savings', 'investment']);
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('currency_symbol');
            $table->foreign('currency_symbol')
                ->references('symbol')
                ->on('currencies')
                ->onDelete('cascade');
            $table->integer('balance')->default(0);
            $table->enum('status', ['active', 'closed', 'blocked'])->default('active');
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
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['currency_symbol']);
        });
    }

    private function dropAccountsTable(): void
    {
        Schema::dropIfExists('accounts');
    }
};
