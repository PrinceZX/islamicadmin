<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('full_name');
            $table->string('email');
            $table->string('password');
            $table->string('mobile_number');
            $table->date('date_of_birth');
            $table->integer('gender')->comment('1 = Male, 2 = Female, 3 = Other');
            $table->string('image');
            $table->integer('type')->comment('1 = OTP, 2 = Social, 3 = Normal');
            $table->text('bio');
            $table->text('device_token');
            $table->integer('status')->default('1');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user');
    }
}
