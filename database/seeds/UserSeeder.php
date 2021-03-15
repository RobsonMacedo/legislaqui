<?php
/**
 * Created by PhpStorm.
 * User: falbernaz
 * Date: 18/01/2016
 * Time: 12:19.
 */

use App\Support\Constants;
use App\Data\Models\User;
use Illuminate\Database\Seeder;
use App\Data\Models\Proposal;
use Symfony\Component\Console\Helper\Helper;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Users and Proposals
        factory(\App\Data\Models\User::class, rand(20, 50))
            ->create()
            ->each(function ($user) {
                foreach (range(1, rand(2, 4)) as $x) {
                    $proposals = factory(
                        \App\Data\Models\Proposal::class,
                        random_int(1, 6)
                    )->create();

                    foreach (range(1, rand(0, 20)) as $y) {
                        // Approvals
                        $proposals->each(function (Proposal $item) use ($user) {
                            $item->approvals()->attach($user->id);
                        });
                    }

                    //Likes
                    factory(\App\Data\Models\Like::class, random_int(0, 30))->create();

                    //Follows
                    factory(\App\Data\Models\ProposalFollow::class, random_int(0, 15))->create();
                }
            });

        //Administrator
        factory(\App\Data\Models\User::class, 'admin', 1)->create([
            'name' => 'Adm',
            'email' => 'adm@test.com',
            'password' => Hash::make('secret'),
        ]);
    }
}
