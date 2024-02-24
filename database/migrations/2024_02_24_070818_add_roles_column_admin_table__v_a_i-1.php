<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddRolesColumnAdminTableVAI1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_admin', function($table) {
            $table->enum('permissions_role', array('super_admin', 'publisher', 'author'))->default('author');
        });
        DB::table('tbl_admin')
            ->where('id', 1)
            ->update(['permissions_role' => 'super_admin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_admin', function($table) {
            $table->dropColumn('permissions_role');
        });
    }
}
