<?php

namespace App\Http\Controllers\Influencer;

use App\Link;
use App\LinkProduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\LinkResource;

class LinkController
{
    public function store(Request $request)
    {
        $link = Link::create([
            'user_id' => $request->user()->id,
            'code' => Str::random(6),
        ]);

        foreach ($request->input('products') as $product_id) {
            LinkProduct::create([
                'link_id' => $link->id,
                'product_id' => $product_id,
            ]);
        }

        return new LinkResource($link);
    }
}
