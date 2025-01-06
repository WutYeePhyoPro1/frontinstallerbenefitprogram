<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointPaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_pays', function (Blueprint $table) {
            $table->id();
            $table->uuid("installer_card_point_uuid");
            $table->integer("before_pay_points_balance");
            $table->decimal("before_pay_amount_balance",19,2);
            $table->integer('points_paid');
            $table->decimal('accept_value', 19, 2);
            $table->uuid("preused_slip_uuid");
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
        Schema::dropIfExists('point_pays');
    }
}
