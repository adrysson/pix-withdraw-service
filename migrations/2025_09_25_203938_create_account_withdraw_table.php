<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_withdraw', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('account_id');
            $table->string('method');
            $table->decimal('amount', 15, 2);
            $table->boolean('scheduled')->default(false);
            $table->datetime('scheduled_for')->nullable();
            $table->boolean('done')->default(false);
            $table->boolean('error')->nullable();
            $table->string('error_reason')->nullable();
            $table->foreign('account_id')->references('id')->on('account')->onDelete('cascade');
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_withdraw');
    }
};
