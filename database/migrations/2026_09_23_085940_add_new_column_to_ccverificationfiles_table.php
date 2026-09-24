<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToCcverificationfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ccverificationfiles', function (Blueprint $table) {
            //
            $table->string('file_type')->nullable()->after('Path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ccverificationfiles', function (Blueprint $table) {
            //
            $table->string('file_type')->nullable()->after('Path');
        });
    }
}
