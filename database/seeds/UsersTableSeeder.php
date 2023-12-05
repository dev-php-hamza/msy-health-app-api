<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$seed = [
    		0 => [
    			'name'       => 'John Doe',
    			'email'      => 'testadmin@example.com',
    			'password'   => Hash::make('admin123'),
                'country_id' => 1,
    			'role_id'    => 1,
    		],
    		1 => [
    			'name'       => 'Mark Vain',
    			'email'      => 'testuser@example.com',
    			'password'   => Hash::make('user123'),
                'country_id' => 1,
    			'role_id'    => 2,
    		]
    	];

    	foreach ($seed as $key => $user) {
    	    $user = User::updateOrCreate([
    	    	'name' => $user['name'],
    	    ],
    	    [
    	    	'email'      => $user['email'],
    	    	'password'   => $user['password'],
                'country_id' => $user['country_id'],
    	    	'role_id'    => $user['role_id'],
    	    ]);
    	}  
    }
}
