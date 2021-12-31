<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class ProductCacheFlush
{
    public function handle($event)
    {
        $productKeys = Cache::get('product_keys');
        if(!empty($productKeys) && is_array($productKeys)){
            foreach($productKeys as $key){
                Cache::forget($key);
            }
        }
        else{
            Cache::forget('products');
        }
        Cache::forget('product_keys');
    }
}
