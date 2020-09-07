<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DoctorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('doctors')->insert([
            'name'       => 'Dra. Adriana Galvão',
            'email'      => 'adrianagalvao@teste.com',
            'image'      => '1.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('doctors')->insert([
            'name'       => 'Dr. Manoel Corte Real',
            'email'      => 'manoelcorte@teste.com',
            'image'      => '2.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('doctors')->insert([
            'name'       => 'Dra. Cecília Nascimento',
            'email'      => 'cecilianascimento@teste.com',
            'image'      => '3.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('doctors')->insert([
            'name'       => 'Dr. Matheus Novaes',
            'email'      => 'matheusnovaes@teste.com',
            'image'      => '4.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('doctors')->insert([
            'name'       => 'Dra. Maria Conceição',
            'email'      => 'mariaconceicao@teste.com',
            'image'      => '5.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('doctors')->insert([
            'name'       => 'Dr. Francisco Benício Cardoso',
            'email'      => 'franciscocardoso@teste.com',
            'image'      => '6.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
