<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::connection('mysql')->create('register_nrb_backup', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('jam');
            $table->string('toko');
            $table->string('nrb');
            $table->integer('koli');
            $table->string('palet');
            $table->string('nama_register');
            $table->string('keterangan');
            $table->datetime('create_dt');
            $table->string('create_by');
            $table->datetime('modify_dt');
            $table->string('modify_by');
            $table->datetime('deleted_at')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps(); // optional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_nrb_backup');
    }
};
