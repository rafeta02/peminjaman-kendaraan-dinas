<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinjamsTable extends Migration
{
    public function up()
    {
        Schema::create('pinjams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('no_wa');
            $table->datetime('date_start');
            $table->datetime('date_end');
            $table->string('reason');
            $table->string('status')->nullable();
            $table->longText('status_text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
