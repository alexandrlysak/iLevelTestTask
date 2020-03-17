<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = ['title'];

    /* Relations */

    /**
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}
