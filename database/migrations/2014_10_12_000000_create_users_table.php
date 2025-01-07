<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->index();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('otp_no')->nullable();
            $table->timestamp('otp_verified_at')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->string('employee_id',12)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('status')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
