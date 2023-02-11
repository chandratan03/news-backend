<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends MyModel
{
    public function NewsCategory()
    {
        return $this->hasOne(NewsCategory::class);
    }
}
