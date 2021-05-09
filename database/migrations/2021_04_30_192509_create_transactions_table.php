<?php

use App\Models\Wallet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
            'transactions',
            function (Blueprint $table) {
                $table->id();
                $table->integer('operation_id')->unsigned()->nullable();
                $table->foreignId('wallet_id')->constrained();
                $table->string('other')->nullable();
                $table->foreignIdFor(Wallet::class, 'other_wallet_id')->nullable();
                $table->unsignedDecimal('debit')->nullable();
                $table->unsignedDecimal('credit')->nullable();
                $table->string('currency');
                $table->boolean('fraudulent')->default(false);
                $table->text('notes');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
}
