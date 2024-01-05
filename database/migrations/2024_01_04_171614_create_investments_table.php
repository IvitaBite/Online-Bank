<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->string('account_number');
            $table->foreign('account_number')
                ->references('account_number')
                ->on('accounts')
                ->onDelete('cascade');
            $table->string('symbol');
            $table->enum('type', ['crypto', 'stock']);
            $table->integer('amount');
            $table->decimal('buy_rate', 30, 15);
            $table->decimal('sell_rate', 30, 15)->default(0);
            $table->enum('status', ['active', 'sold'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->dropForeign(['account_number']);
        });

        Schema::dropIfExists('investments');
    }
};
