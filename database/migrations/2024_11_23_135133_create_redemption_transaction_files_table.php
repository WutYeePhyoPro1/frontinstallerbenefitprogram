<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedemptionTransactionFilesTable extends Migration
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
        Schema::create('redemption_transaction_files', function (Blueprint $table) {
            $table->id();
            $table->uuid("redemption_transaction_uuid");
            $table->string("image");
            $table->uuid("user_uuid");
            // $table->foreign('redemption_transaction_uuid')->references('uuid')->on('redemption_transactions')->onDelete('cascade');
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
        Schema::dropIfExists('redemption_transaction_files');
    }
}
