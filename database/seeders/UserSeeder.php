<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User();
        $admin->nama = 'admin';
        $admin->nip = 'ADM001';
        $admin->password = bcrypt('12345678');
        $admin->passwordAsli = '12345678';
        $admin->roleId = 1;
        $admin->save();
    }
}
