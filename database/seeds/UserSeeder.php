<?php

use Illuminate\Database\Seeder;
use App\Admin;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create(
            [
                'name' => 'Super Admin',
                'email' => 'saktanainsebaa@gmail.com',
                'password' => bcrypt('super_admin')
            ]
        );

        $admin->attachRole('super_admin');

    }
}
