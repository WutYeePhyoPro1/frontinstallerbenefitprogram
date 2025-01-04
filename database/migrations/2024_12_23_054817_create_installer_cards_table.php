<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('installer_cards', function (Blueprint $table) {
            $table->id();
            $table->string("card_number");
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
            $table->timestamp('issued_at');
            $table->uuid('user_uuid');
            $table->integer('status')->default(1);

            $table->timestamps();
            // $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installer_cards');
    }
};
