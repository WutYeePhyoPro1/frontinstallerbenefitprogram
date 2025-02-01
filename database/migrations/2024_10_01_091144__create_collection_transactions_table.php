<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionTransactionsTable extends Migration
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
        Schema::create('collection_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->uuid("point_promotion_uud");
            $table->decimal("points_award_rate",19,2);
            $table->tinyInteger('branch_id');
            $table->string('document_no');
            $table->string('installer_card_card_number');
            $table->string("invoice_number");
            $table->decimal('total_sale_cash_amount', 19, 2);
            $table->integer('total_points_collected');
            $table->decimal('total_save_value', 19, 2);
            $table->dateTime('collection_date');
            $table->uuid("user_uuid");
            $table->date('buy_date')->nullable();
            $table->bigInteger('gbh_customer_id')->nullable();
            $table->bigInteger('sale_cash_document_id')->nullable();
            $table->string('branch_code',10)->nullable();
            // $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('collection_transactions');
    }
}
