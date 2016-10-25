<?php

use Illuminate\Database\Seeder;

use App\Reservation;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::statement('SET FOREIGN_KEY_CHECKS = 0');

         Reservation::truncate();
         factory(Reservation::class,800)->create();
    }
}
