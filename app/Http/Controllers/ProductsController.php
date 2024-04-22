<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get product details
        // $productList = Products::select('products.id', 'products.name', 'products.price')
        //     ->orderBy('products.id', 'asc')
        //     ->get();

        $productList = Products::paginate(10);

        return view('products.index', compact('productList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            // Validate the submitted form data
            $validatedData = $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
            ]);

            // Create a new reservation and submit the data to the database
            $product = new Products();
            $product->name = $validatedData['name'];
            $product->price = $validatedData['price'];
            $product->save();

            // Redirect back with success message
            return redirect()->route('products.index')->with('success', 'Product successfully created');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $products)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Products $products)
    {
        //
    }
}
