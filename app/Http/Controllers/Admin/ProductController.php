<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class ProductController extends Controller
{
    public function Index(){

        $products = Product::with('category')->get();
        $categories = Category::all(); // Fetch categories if needed

        return view('admin.product', compact('products', 'categories'));
        
    }

    public function importProducts(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        Excel::import(new ProductsImport, $request->file('file'));

        return response()->json(['message' => 'Products imported successfully.']);
    }
}
