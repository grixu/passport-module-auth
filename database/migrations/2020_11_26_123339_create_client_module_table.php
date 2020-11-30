<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateClientModuleTable
 */
class CreateClientModuleTable extends Migration
{
    public function up()
    {
        Schema::create('client_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id');
            $table->string('module');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('client_modules');
    }
}
