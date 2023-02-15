<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends MyModel
{
    public function newsCategory()
    {
        return $this->belongsTo(NewsCategory::class);
    }

    public function newsContributors()
    {
        return $this->hasMany(NewsContributor::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }
}
