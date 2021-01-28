<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ProductCollection(Product::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "title" => ['required', 'string'],
            "description" => ['required', 'string'],
            "price" => ['required', 'numeric'],
            "image" => ['required', 'file'],
        ]);

        if ($request->hasFile('image')) {
            $image_path = Storage::disk("local")->put("images", $request->image);
        }

        $data = [
            "title" => $request->title,
            "description" => $request->description,
            "price" => $request->price,
            "image" => $image_path,
        ];

        $product = new Product($data);

        if ($product->save()) {
            return response()->json([
                "success" => true,
                "status" => 201,
                "statusText" => "success",
                "message" => "Successfully Product Created",
                "data" => new ProductResource($product),
            ]);
        } else {
            return response()->json([
                "success" => false,
                "status" => 400,
                "statusText" => "error",
                "message" => "Something went wrong!",
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            "title" => ['required', 'string'],
            "description" => ['required', 'string'],
            "price" => ['required', 'numeric'],
            "image" => ['nullable', 'file'],
        ]);

        $data = [
            "title" => $request->title,
            "description" => $request->description,
            "price" => $request->price,
        ];

        if ($request->hasFile('image')) {
            if (Storage::disk("local")->exists($product->image)) {
                Storage::disk("local")->delete($product->image);
            }
            $data["image"] = Storage::disk("local")->put("images", $request->image);
        }

        $product->fill($data);

        if ($product->save()) {
            return response()->json([
                "success" => true,
                "status" => 200,
                "statusText" => "success",
                "message" => "Successfully Product Updated",
                "data" => new ProductResource($product),
            ]);
        } else {
            return response()->json([
                "success" => false,
                "status" => 400,
                "statusText" => "error",
                "message" => "Something went wrong!",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (Storage::disk("local")->exists($product->image)) {
            Storage::disk("local")->delete($product->image);
        }

        if ($product->delete()) {
            return response()->json([
                "success" => true,
                "status" => 200,
                "statusText" => "success",
                "message" => "Successfully Product Deleted.",
            ]);
        } else {
            return response()->json([
                "success" => false,
                "status" => 400,
                "statusText" => "error",
                "message" => "Something went wrong!",
            ]);
        }
    }
}
