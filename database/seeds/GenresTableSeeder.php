<?php

use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genres')->insert([
            'name' => 'Rock',
            'cover'=>'audio/genres/rock.jpg'
        ]);

        DB::table('genres')->insert([
            'name' => 'Pop',
            'cover'=>'audio/genres/pop.jpg'
        ]);

        DB::table('genres')->insert([
            'name' => 'Metal',
            'cover'=>'audio/genres/metal.jpg'
        ]);

        DB::table('genres')->insert([
            'name' => 'Classic',
            'cover'=>'audio/genres/classic.jpg'
        ]);

        DB::table('genres')->insert([
            'name' => 'Electronic',
            'cover'=>'audio/genres/electronic.jpg'
        ]);

        DB::table('genres')->insert([
            'name' => 'jazz',
            'cover'=>'audio/genres/jazz.jpg'
        ]);

        DB::table('genres')->insert([
            'name' => 'blues',
            'cover'=>'audio/genres/blues.jpg'
        ]);

        DB::table('genres')->insert([
            'name' => 'country',
            'cover'=>'audio/genres/country.jpg'
        ]);

        DB::table('genres')->insert([
            'name' => 'punk',
            'cover'=>'audio/genres/punk.jpg'
        ]);

        DB::table('genres')->insert([
            'name' => 'alternative',
            'cover'=>'audio/genres/alternative.jpg'
        ]);

        DB::table('genres')->insert([
            'name' => 'rap',
            'cover'=>'audio/genres/rap.jpg'
        ]);

        DB::table('genres')->insert([
            'name' => 'soundtrack',
            'cover'=>'audio/genres/soundtrack.png'
        ]);

    }
}
