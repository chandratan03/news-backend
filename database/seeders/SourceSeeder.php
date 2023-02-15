<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $sources = ["Guardian", "News API", "NY Times"];

        foreach($sources as $source){
            $newsCategory = new Source();
            $newsCategory["source_name"] = $source;
            $newsCategory->save();
        }
    }
}
