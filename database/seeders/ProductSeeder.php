<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Local;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use \Bezhanov\Faker\Provider\Commerce;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = explode(',', 'Abarrotes, Frutas y Verduras, Panaderia, Limpieza, Hogar, Mascotas, Lacteos y Huevos, Carnes, Bebidas');

        foreach ($categories as $cat_name) {
            Category::create(['users_id' => 1, 'name' => $cat_name]);
        }

        $faker = \Faker\Factory::create();
        $faker->locale('es_ES');
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        foreach (File::allFiles('/mnt/c/Documents and Settings/ACER/Desktop/products') as $file) {
            $name = $faker->productName();

            $files_info[] = array(
                'filename' => $file->getFilename(),
                'filesize' => $file->getSize(),  // returns size in bytes
                'fileext'  => $file->getExtension()
            );

            $cat_id = fake()->numberBetween(1, count($categories) - 1);

            $new_product = [
                'stores_id'             => fake()->numberBetween(1, 7),
                'product_categories_id' => $cat_id,
                'name'                  => substr($categories[$cat_id], 0,12) . ' ' . substr( $name ,10),
                'description'           => $faker->text(),
                'image'                 => $file->getFilename(),
                'price'                 => 0.70, // $faker->randomFloat(2, 10, 30),
                'quantity'              => $faker->numberBetween(1000, 5000)
            ];
            Product::create($new_product);
        }

        $order_limit = 1000;

        $output = new ConsoleOutput();
        $progress = new ProgressBar($output, $order_limit);
        $progress->start();


        // create 1000 orders from last 60 days
        for ($n = 1; $n <= $order_limit; $n++) {
            //$user_id=fake()->numberBetween(1, 3);
            $store_id       = fake()->numberBetween(1, 7);
            $store_obj      = Local::where('id', $store_id)->first();
            $status         = ['process', 'canceled', 'completed'];
            $status_id      = fake()->numberBetween(1, count($status) - 1);
            $date_completed = null;
            if ($status_id == 2) {
                $date_completed = date('Y-m-d', strtotime('-' . rand(0, 70) . ' days'));
            }
            $status_value = $status[$status_id];
            $new_order    =
                ['users_id' => $store_obj->users_id, 'stores_id' => $store_id, 'currency' => 'ltc', 'status' => $status_value, 'completed_at' => $date_completed];
            $order        = Order::create($new_order);
            $random_qty   = rand(1, 2);
            $products     = $store_obj->products->random($random_qty);

            $orders_array =[];

            foreach ($products as $product) {
                if ($product->quantity != 0) {
                    $qty               = 1;//rand(1,1);
                    $order_item        = OrderItem::create(['order_id' => $order->id, 'quantity' => $qty, 'product_id' => $product->id, 'price' => $product->price]);
                    $product->quantity = $product->quantity - $qty;
                    $product->save();
                }
            }
            $progress->advance();


        }
        $progress->finish();


    }
}
