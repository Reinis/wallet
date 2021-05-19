<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTransactionsOtherColumn extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(
            'transactions',
            function (Blueprint $table) {
                $table->dropColumn('other');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'transactions',
            function (Blueprint $table) {
                $table->after(
                    'wallet_id',
                    function (Blueprint $table) {
                        $table->string('other')->nullable();
                    }
                );
            }
        );
    }
}
