<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $categoryPrinters = Category::factory()->create(["name" => "Принтеры"]);
        $categoryOfficeAutomation = Category::factory()->create(["name" => "Оргтехника"]);
        $categoryPens = Category::factory()->create(["name" => "Ручки"]);


        $itemPen = Item::factory()->create([
            "name" => "Ручка",
            "price" => 50,
            "is_published" => false,
            "is_deleted" => false,
        ]);

        $itemPrinter = Item::factory()->create([
            "name" => "Принтер HP LaserJet 1300",
            "price" => 5990,
            "is_published" => true,
            "is_deleted" => false,
        ]);

        $categoryPrinters->items()->attach($itemPrinter);
        $categoryOfficeAutomation->items()->attach($itemPrinter);
        $categoryPens->items()->attach($itemPen);
    }
}
