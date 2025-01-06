<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnBannersTable extends Migration
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
        Schema::create('return_banners', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->tinyInteger('branch_id');
            $table->string("installer_card_card_number");
            $table->string('return_product_docno');
            $table->string('ref_invoice_number');
            $table->decimal('total_return_value', 19, 2);
            $table->integer("total_return_points");
            $table->decimal("total_return_amount",19,2);
            $table->uuid("collection_transaction_uuid");
            $table->uuid("reference_return_collection_transaction_uuid");
            $table->dateTime('return_action_date');
            $table->uuid('user_uuid');
            // $table->foreign('collection_transaction_uuid')->references('uuid')->on('collection_transactions')->onDelete('cascade');
            // $table->foreign('reference_return_collection_transaction_uuid')->references('uuid')->on('reference_return_collection_transactions')->onDelete('cascade');
            // $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
            $table->date('return_date');
            $table->bigInteger('gbh_customer_id');
            $table->bigInteger('sale_cash_document_id');
            $table->string('return_product_doc_branch_code',10);
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
        Schema::dropIfExists('return_banners');
    }
}
