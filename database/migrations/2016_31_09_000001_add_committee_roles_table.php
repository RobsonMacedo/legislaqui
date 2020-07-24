<?php
/**
 * Created by PhpStorm.
 * User: falbernaz
 * Date: 04/03/2016
 * Time: 12:15.
 */

use App\Support\Constants;
use Illuminate\Database\Migrations\Migration;

class AddCommitteeRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->seed();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->unseed();
    }

    private function seed()
    {
        DB::table('roles')->insert(['role' => Constants::ROLE_COMMISSION]);
    }

    private function unseed()
    {
        DB::table('roles')
            ->where('id', '=', 2)
            ->delete();
    }
}
