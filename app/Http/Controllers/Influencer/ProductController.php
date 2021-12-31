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
		if($request->input('s')){
			$key = "products_{$request->input('s')}";
		}
		else{
			$key = "products";
		}

		return Cache::remember($key, 60*30, function() use ($request){
			$products = Product::all();

			if ($s = $request->input('s')) {
				$products = $products->filter(function (Product $product) use ($s) {
					return Str::contains($product->title, $s) || Str::contains($product->description, $s);
				});
			}
	
			return  ProductResource::collection($products);
		});

    }
}