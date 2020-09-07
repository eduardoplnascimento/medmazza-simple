<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PatientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('patients')->insert([
            'name'       => 'Eduardo Nascimento',
            'email'      => 'eduardonascimento@teste.com',
            'blood_type' => 'O+',
            'image'      => 'F6fMQY.jpeg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
