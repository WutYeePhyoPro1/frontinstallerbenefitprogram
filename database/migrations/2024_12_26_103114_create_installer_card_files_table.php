<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallerCardFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installer_card_files', function (Blueprint $table) {
            $table->id();
            $table->string("installer_card_card_number");
            $table->string("image");
            $table->foreign('installer_card_card_number')->references('card_number')->on('installer_cards')->onDelete('cascade');
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
        Schema::dropIfExists('installer_card_files');
    }
}
