<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $faker = Faker::create();
    	foreach (range(1,2) as $index) {
	        DB::table('players')->insert([
	            'first_name' => $faker->firstNameMale(),
                'last_name' => $faker->lastName(),
                'position' => 'striker',
	            'rating' => $faker->numberBetween(55,100),
	        ]);
	}
    }
}
