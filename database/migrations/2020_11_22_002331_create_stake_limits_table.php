<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStakeLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stake_limits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('deviceId');
            $table->foreign('deviceId')->references('id')->on('devices');
            $table->unsignedInteger('expiresFor');
            $table->decimal('blockValue', 8, 2);
            $table->decimal('hotValue', 8, 2);
            $table->timestamp('validFrom')->default(\Carbon\Carbon::now());
            $table->timestamp('validTo');
            $table->timestamp('expiresAt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stake_limits');
    }
}
