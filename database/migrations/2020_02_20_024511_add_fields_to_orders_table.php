<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('first_name');
            $table->text('address');
            $table->string('last_name', 50);
            $table->string('phone', 20);
            $table->integer('zip');
            $table->string('email', 50);
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('address');
            $table->dropColumn('last_name');
            $table->dropColumn('phone');
            $table->dropColumn('zip');
            $table->dropColumn('email');
            $table->dropColumn('user_id');
        });
    }
}
