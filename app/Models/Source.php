<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends MyModel
{
    public function news(){
        return $this->hasMany(News::class, "news_source_id");
    }
}
