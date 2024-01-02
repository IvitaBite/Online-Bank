<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $this->createAccountNumberField($table, 'account_number_from');
            $this->createAccountNumberField($table, 'account_number_to');
            $table->integer('amount')->default(0);
            $table->string('currency_symbol_from');
            $table->string('currency_symbol_to')->nullable();
            $table->decimal('exchange_rate', 30, 15)->nullable();
            $table->string('description')->nullable();
            $table->string('type');
            $table->enum('status', ['Pending', 'Completed', 'Failed']);
            $table->boolean('is_confirmed')->default(false);
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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['account_number_from']);
            $table->dropForeign(['account_number_to']);
        });
    }

    private function dropAccountsTable(): void
    {
        Schema::dropIfExists('transactions');
    }

    private function createAccountNumberField(Blueprint $table, string $fieldName): void
    {
        $table->string($fieldName);
        $table->foreign($fieldName)
            ->references('account_number')
            ->on('accounts')
            ->onDelete('cascade');
    }
};
