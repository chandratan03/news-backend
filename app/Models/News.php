<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends MyModel
{
    public function newsCategory()
    {
        return $this->hasOne(NewsCategory::class);
    }

    public function newsContributors()
    {
        return $this->hasMany(NewsContributor::class);
    }
}
