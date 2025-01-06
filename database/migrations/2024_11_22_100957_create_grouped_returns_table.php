<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupedReturnsTable extends Migration
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
        Schema::create('grouped_returns', function (Blueprint $table) {
            $table->id();
            $table->uuid("installer_card_point_uuid");
            $table->uuid("reference_return_installer_card_point_uuid");
            $table->tinyInteger('maincatid');
            $table->string("category_remark");
            $table->tinyInteger('category_id');
            $table->string("category_name");
            $table->tinyInteger('group_id');
            $table->string("group_name");
            $table->decimal("return_price_amount",19,2);
            $table->integer("return_point");
            $table->decimal("return_amount",19,2);
            $table->uuid("return_banner_uuid");
            // $table->foreign('reference_return_installer_card_point_uuid')->references('uuid')->on('reference_return_installer_card_points')->onDelete('cascade');
            // $table->foreign('return_banner_uuid')->references('uuid')->on('return_banners')->onDelete('cascade');
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
        Schema::dropIfExists('grouped_returns');
    }
}
