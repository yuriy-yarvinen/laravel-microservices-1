<?php

use App\Role;
use App\User;
use App\UserRole;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 20)->create();

        $users = User::all();

        foreach ($users as $user) { 
            UserRole::create([
                'user_id'=>$user->id,
                'role_id'=>Role::inRandomOrder()->first()->id
            ]);
        }
    }
}
