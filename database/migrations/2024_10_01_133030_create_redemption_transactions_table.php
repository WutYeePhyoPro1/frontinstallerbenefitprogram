<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedemptionTransactionsTable extends Migration
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
        Schema::create('redemption_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->tinyInteger('branch_id');
            $table->string('document_no');
            $table->string("installer_card_card_number");
            $table->integer('total_points_redeemed');
            $table->decimal('total_cash_value', 19, 2);
            $table->enum('status', ['pending', 'approved', 'rejected','paid','finished'])->default('pending');
            $table->dateTime('redemption_date');
            $table->string("requester");
            $table->uuid('prepare_by');
            $table->uuid('approved_by')->nullable();
            $table->uuid('paid_by')->nullable();
            $table->dateTime("approved_date")->nullable();
            $table->dateTime("paid_date")->nullable();
            // $table->foreign('approved_by')->references('uuid')->on('users')->onDelete('cascade');
            // $table->foreign('prepare_by')->references('uuid')->on('users')->onDelete('cascade');
            // $table->foreign('paid_by')->references('uuid')->on('users')->onDelete('cascade');
            $table->text('remark')->nullable();
            $table->text('bm_remark')->nullable();
            $table->text('ac_remark')->nullable();
            $table->string('nature');
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
        Schema::dropIfExists('redemption_transactions');
    }
}
