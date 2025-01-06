<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallerCardsTable extends Migration
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
        Schema::create('installer_cards', function (Blueprint $table) {
            $table->id();
            $table->string("card_number")->unique();
            $table->tinyInteger('branch_id');

            $table->string("fullname");
            $table->string("phone");
            $table->string("address");
            $table->string("gender",10);
            $table->date("dob");
            $table->string("nrc");
            $table->string("passport")->nullable();
            $table->string('identification_card')->nullable();
            $table->boolean('member_active');
            $table->boolean('customer_active');
            $table->bigInteger("customer_rank_id");
            $table->string('customer_barcode');

            $table->string("titlename");
            $table->string("firstname");
            $table->string("lastnanme")->nullable();
            $table->bigInteger("province_id");
            $table->bigInteger("amphur_id");
            $table->string("nrc_no");
            $table->string("nrc_name");
            $table->string("nrc_short");
            $table->string("nrc_number");
            $table->bigInteger("gbh_customer_id");


            $table->integer('totalpoints')->default(0);
            $table->decimal("totalamount",19,2)->default(0);
            $table->integer('credit_points')->default(0);
            $table->decimal("credit_amount",19,2)->default(0);
            $table->integer('expire_points')->default(0);
            $table->decimal("expire_amount",19,2)->default(0);
            $table->timestamp('issued_at');
            $table->uuid('user_uuid');
            $table->integer('status')->default(1);

            $table->uuid('approved_by')->nullable();
            $table->dateTime("approved_date")->nullable();
            $table->text('bm_remark')->nullable();
            $table->enum('stage', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('installer_cards');
    }
}
