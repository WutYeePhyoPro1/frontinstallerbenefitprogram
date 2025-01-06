<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreusedSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preused_slips', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->tinyInteger('branch_id');
            $table->string('installer_card_card_number');
            $table->integer('before_pay_total_points')->default(0);
            $table->decimal("before_pay_total_amount",19,2)->default(0);
            $table->integer('before_pay_credit_points')->default(0);
            $table->decimal("before_pay_credit_amount",19,2)->default(0);
            $table->integer('total_points_paid');
            $table->decimal('total_accept_value', 19, 2);
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
        Schema::dropIfExists('preused_slips');
    }
}
