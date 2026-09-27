<?php

namespace Database\Seeders;

use App\Models\EmailContact;
use Illuminate\Database\Seeder;

class EmailContactSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        if (app()->environment('local')) {
            EmailContact::factory(1000)->create();
        }
    }
}
