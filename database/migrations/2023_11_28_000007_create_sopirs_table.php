<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSopirsTable extends Migration
{
    public function up()
    {
        Schema::create('sopirs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nip');
            $table->string('nama');
            $table->string('no_wa')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
