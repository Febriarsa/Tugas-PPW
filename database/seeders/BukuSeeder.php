<?php

namespace Database\Seeders;

use App\Models\Buku;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            Buku::create([
                'judul' => $faker->sentence(3),
                'penulis' => $faker->name,
                'harga' => $faker->numberBetween(10000, 50000),
                'tgl_terbit' => $faker->date(),
            ]);
        }
    }

}
