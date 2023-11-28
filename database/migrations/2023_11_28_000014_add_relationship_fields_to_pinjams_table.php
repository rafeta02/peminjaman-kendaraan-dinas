<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPinjamsTable extends Migration
{
    public function up()
    {
        Schema::table('pinjams', function (Blueprint $table) {
            $table->unsignedBigInteger('kendaraan_id')->nullable();
            $table->foreign('kendaraan_id', 'kendaraan_fk_9245667')->references('id')->on('kendaraans');
            $table->unsignedBigInteger('borrowed_by_id')->nullable();
            $table->foreign('borrowed_by_id', 'borrowed_by_fk_9245674')->references('id')->on('users');
            $table->unsignedBigInteger('processed_by_id')->nullable();
            $table->foreign('processed_by_id', 'processed_by_fk_9245675')->references('id')->on('users');
            $table->unsignedBigInteger('sopir_id')->nullable();
            $table->foreign('sopir_id', 'sopir_fk_9245676')->references('id')->on('sopirs');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_9245684')->references('id')->on('users');
        });
    }
}
