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
     * Show the form for editing the specified resource.
     */
    public function edit(Products $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $product)
    {
        // Validate the submitted form data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric'
        ]);

        // Update the product and submit the new data to the database
        // $product = Products::findOrFail($productId);
        $product->name = $validatedData['name'];
        $product->price = $validatedData['price'];
        $product->save();

        // Redirect with success message
        return redirect()->route('products.index')->with('success', 'Product has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Products $product)
    {
        // try to delete the provided product
        try {
            $product->delete();

            // Redirect to index with a success message
            return redirect()->route('products.index')->with('success', 'Product successfully deleted');
        } catch (\Exception $e) {
            // Handle any other exceptions or errors that might occur during deletion
            // Redirect back with those error messages
            return redirect()->route('products.index')->with('error', 'product couldnt be deleted ' . $e->getMessage());
        }
    }
}
