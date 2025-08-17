<?php

namespace Database\Seeders;

use App\Models\Local;
use Illuminate\Database\Seeder;
use File;
use App\Models\LocalType;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);


        $faker = \Faker\Factory::create();
        $faker->locale('es_ES');
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));


        //$contents = File::get(base_path('database/seeders/store_1_row.json'));
        $contents = File::get(base_path('database/seeders/locales.json'));

        $locales_json = json_decode(json: $contents, associative: true);

        $faker = \Faker\Factory::create();
        //$faker->addProvider(new Faker\Provider\Lorem($faker));

        $categories = explode(',', 'Mini-Super,Verdureria,Carniceria,Restaurante,Farmacia,Repuestos,Lavacar,Taller,Peluqueria,Veterinaria,Ferreteria,Bodega,Oficina,Tienda,Gasolinera,Gymnasio');
        $categories_icons = explode(',', 'la-store-alt,la-store-alt,la-store-alt,la-utensils,la-clinic-medical,la-clinic-medical,la-car-side,la-oil-can,la-cut,la-dog,la-tools,la-building,la-industry,la-store,la-gas-pump,la-running');
        $cursor=0;
        foreach ($categories as $cat_name) {
            LocalType::create([
                                'name' => $cat_name,
                                'icon' => $categories_icons[$cursor]

                                ]);
            $cursor++;
        }


        foreach ($locales_json as $local) {

            $tipo = LocalType::all()->random(1)->first();
            //dd($tipo);

            $user_id=fake()->numberBetween(1, 3);

            $local['users_id']=$user_id;

            Local::factory()->create([
                'users_id'            => $local['users_id'],
                'store_categories_id' => $tipo->id,
                'name'                => $local['name'],
                'position_lng'        => $local['lang'],
                'position_lat'        => $local['lat'],
                'phone'               => $faker->PhoneNumber(),
                'logo'                => 'n-image.png',
                'description'         => $faker->realText(255)
            ]);
        }

        $this->call(ProductSeeder::class);

        $this->call(SettingsSeeder::class);
    }
}
