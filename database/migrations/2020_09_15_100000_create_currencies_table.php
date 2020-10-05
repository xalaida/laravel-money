<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currencies', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 3)->unique()->comment('ISO 4217');
            $table->string('name', 50);
            $table->string('symbol', 10)->nullable();
            $table->tinyInteger('precision');
            $table->float('rate')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
}
