<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsRedemptionsTable extends Migration
{
    // public function __construct()
    // {
    //     $this->connection = 'centralpgsql';
    // }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points_redemptions', function (Blueprint $table) {
            $table->id();
            $table->uuid("installer_card_point_uuid");
            $table->integer("points_redeemed");
            $table->decimal("point_accumulated",19,2);
            $table->decimal("redemption_amount",19,2);
            // $table->dateTime("redemption_date");
            $table->uuid("redemption_transaction_uuid");
            // $table->foreign('redemption_transaction_uuid')->references('uuid')->on('redemption_transactions')->onDelete('cascade');
            // $table->foreign('installer_card_point_uuid')->references('uuid')->on('installer_card_points')->onDelete('cascade');
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
        Schema::dropIfExists('points_redemptions');
    }
}
