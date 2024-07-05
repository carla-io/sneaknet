<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

use App\Models\Product;

class ProductController extends Controller
{
    public function Index(){

        $products = Product::with('category')->get();
        $categories = Category::all(); // Fetch categories if needed

        return view('admin.product', compact('products', 'categories'));
        
    }
}
