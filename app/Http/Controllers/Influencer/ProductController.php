<?php

namespace App\Http\Controllers\Influencer;

use App\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\ProductResource;

class ProductController
{
    public function index(Request $request)
    {
		$result = Cache::get('products');

		if($result){
			return $result;
		}

		sleep(2);

        $products = Product::all();

        if ($s = $request->input('s')) {
            $products = $products->filter(function (Product $product) use ($s) {
                return Str::contains($product->title, $s) || Str::contains($product->description, $s);
            });
        }

		$resource = ProductResource::collection($products);

		Cache::set('products', $resource, 5);

        return $resource;
    }
}