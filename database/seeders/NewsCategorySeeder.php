<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ["business", "sport", "science"];

        foreach($categories as $category){
            $newsCategory = new NewsCategory();
            $newsCategory["news_category_name"] = $category;
            $newsCategory->save();
        }
    }
}
