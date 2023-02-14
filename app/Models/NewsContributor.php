<?php

namespace App\Models;

class NewsContributor extends MyModel
{
    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function contributor()
    {
        return $this->belongsTo(Contributor::class, "contributor_id");
    }
}
