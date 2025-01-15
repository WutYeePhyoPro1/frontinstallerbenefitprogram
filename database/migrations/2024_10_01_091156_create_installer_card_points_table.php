<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallerCardPointsTable extends Migration
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
        Schema::create('installer_card_points', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('installer_card_card_number');
            $table->tinyInteger('maincatid');
            $table->string("category_remark");
            $table->tinyInteger('category_id');
            $table->string("category_name");
            $table->tinyInteger('group_id');
            $table->string("group_name");
            $table->decimal("saleamount",19,2);
            $table->integer("points_earned");
            $table->integer("points_redeemed");
            $table->integer("points_balance");
            $table->decimal("point_based",19,2);
            $table->decimal("amount_earned",19,2);
            $table->decimal("amount_redeemed",19,2);
            $table->decimal("amount_balance",19,2);
            $table->integer('preused_points')->default(0);
            $table->decimal("preused_amount",19,2)->default(0);
            $table->dateTime("expiry_date");
            $table->enum('is_redeemed', ['0', '1'])->default('0');
            $table->enum('is_returned', ['0', '1'])->default('0');
            $table->uuid("collection_transaction_uuid");
            $table->dateTime('expire_deduction_date')->nullable();
            // $table->foreign('collection_transaction_uuid')->references('uuid')->on('collection_transactions')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['uuid','installer_card_card_number', 'deleted_at']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('installer_card_points');
    }
}
