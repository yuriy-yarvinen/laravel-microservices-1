<?php

namespace App;

use App\User;
use App\Product;
use App\LinkProduct;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, LinkProduct::class);
    }
}
