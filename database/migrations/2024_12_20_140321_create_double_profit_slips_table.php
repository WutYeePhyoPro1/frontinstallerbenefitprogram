<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoubleProfitSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('double_profit_slips', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->tinyInteger('branch_id');
            $table->string('installer_card_card_number');
            $table->uuid("collection_transaction_uuid");
            $table->uuid("user_uuid");
            $table->uuid("redemption_transaction_uuid");
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
        Schema::dropIfExists('double_profit_slips');
    }
}
