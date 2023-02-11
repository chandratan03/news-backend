<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsContributor extends MyModel
{
    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function contributor()
    {
        return $this->belongsTo(Contributor::class);
    }
}
