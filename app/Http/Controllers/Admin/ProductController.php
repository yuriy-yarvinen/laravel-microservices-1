<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Events\ProductUpdatedEvent;
use App\Http\Resources\ProductResource;
use Symfony\Component\HttpFoundation\Response;

class ProductController
{

    public function index()
    {
        (new UserService())->allows('view', 'products');

        $products = Product::paginate();

        return ProductResource::collection($products);
    }

    public function store(Request $request)
    {

        (new UserService())->allows('edit', 'products');

        $data = $request->validate([
            'title' => 'required',
            'image' => 'required',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        $product = Product::create([
            'title' => $data['title'],
            'description' => isset($data['description']) ? $data['description'] : null,
            'price' => $data['price'],
            'image' => $data['image'],
        ]);

        event(new ProductUpdatedEvent());

        return response($product, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        (new UserService())->allows('view', 'products');

        $product = Product::findOrFail($id);

        return new ProductResource($product);
    }

    public function update(Request $request, $id)
    {
        (new UserService())->allows('edit', 'products');

        $product = Product::findOrFail($id);

        $product->update($request->only('title', 'description', 'price', 'image'));

        event(new ProductUpdatedEvent());

        return response($product, Response::HTTP_ACCEPTED);
    }


    public function destroy($id)
    {
        (new UserService())->allows('edit', 'products');

        Product::destroy($id);

        event(new ProductUpdatedEvent());

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
