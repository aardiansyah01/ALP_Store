<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewProductNotification;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort   = $request->input('sort', 'name');
        $order  = $request->input('order', 'asc');
        $category = $request->input('category');

        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');

        $products = Product::query();

        // Search
        if ($search) {
            $products->where('name', 'like', '%' . $search . '%');
        }

        // Filter category
        if ($category) {
            $products->whereHas('category', function ($q) use ($category) {
                $q->where('name', $category);
            });
        }

        // Filter range harga
        if ($min_price !== null) {
            $products->where('price', '>=', $min_price);
        }

        if ($max_price !== null) {
            $products->where('price', '<=', $max_price);
        }

        // sort
        $products->orderBy($sort, $order);

        // Pagination 25
        $products = $products->paginate(25)->withQueryString();

        return view('products.list', compact(
            'products',
            'search',
            'sort',
            'order',
            'category',
            'min_price',
            'max_price'
        ));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function create()
    {
        $categories = \App\Models\Category::pluck('name', 'id');
        
        return view('products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'color'       => 'required|string',
            'category_id' => 'required',
            'image'       => 'nullable|string',
            'stocks'      => 'nullable|array',
            'location'    => 'nullable|string',
            'sizes'       => 'nullable|array',
        ]);

        $product = Product::create($validated);

        foreach ($request->stocks ?? [] as $size => $stock) {
            \App\Models\ProductStock::create([
                'product_id' => $product->id,
                'size'       => $size,
                'stock'      => $stock ?? 0,
            ]);
        }
        
        $users = User::where('role', 'user')->get();

        foreach ($users as $user) {
            $user->notify(new NewProductNotification($product));
        }

        return redirect()->route('products.index')->with('success', 'Product added!');
    }


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = \App\Models\Category::pluck('name', 'id');

        return view('products.form', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'color'       => 'required|string',
            'category_id' => 'required',
            'image'       => 'nullable|string',
            'location'    => 'nullable|string',
            'sizes'       => 'nullable|array',
            'stocks'      => 'nullable|array',
        ]);

        $product = Product::with('stocks')->findOrFail($id);

        // update product utama
        $product->update($validated);

        // reset stok lama
        $product->stocks()->delete();

        // simpan stok per size
        foreach ($request->stocks ?? [] as $size => $stock) {
            \App\Models\ProductStock::create([
                'product_id' => $product->id,
                'size'       => $size,
                'stock'      => (int) $stock,
            ]);
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Updated successfully!');
    }
}





