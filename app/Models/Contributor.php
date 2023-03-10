<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contributor extends MyModel
{
    public function newsContributors()
    {
        return $this->hasMany(NewsContributor::class);
    }
}
