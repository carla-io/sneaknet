<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Searchable\Searchable;



class ProductController extends Controller
{

    //Product Listing
    public function index(Request $request){

        $products = Product::with('category')->get();
        return response()->json([
            'status' => true,
            'message' => "Product Listed Successfully",
            'data' => $products,
        ], 200);
    }

    //Add Product
    public function create(Request $request){

        $validateProduct = Validator::make($request->all(),[
            'product_name' => 'required|unique:products,product_name',
            'price' => 'required|decimal:0,2',
            'category_id' => 'required|exists:categories,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($validateProduct->fails()){
            return response()->json([
                'status' => false,
                'message' => "validation error",
                'data' => $validateProduct->errors(),
            ], 422);
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
        }

        $inputData = array(
            'product_name' => $request->product_name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $imageName,
        );

        $products = Product::create($inputData);

        return response()->json([
            'status' => true,
            'message' => "Product Added Successfully",
            'data' => $products,
        ], 200);

    }

     //Update Product
    public function update(Request $request){
        $validateProduct = Validator::make($request->all(),[
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required',
            'price' => 'required|decimal:0,2',
            'category_id' => 'required|exists:categories,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($validateProduct->fails()){
            return response()->json([
                'status' => false,
                'message' => "validation error",
                'data' => $validateProduct->errors(),
            ], 422);
        }
         
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
            ], 404);
        }

        // Update product details
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->category_id = $request->category_id;

        // Handle image update if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $product->image = $imageName; // Update image name or path
        }

        $product->save();

        return response()->json([
            'status' => true,
            'message' => "Product updated successfully",
            'data' => $product,
        ], 200);

    }

    // Delete Product
    public function delete(Request $request){
        $validateProduct = Validator::make($request->all(),[
            'product_id' => 'required|exists:products,id',
        ]);

        if($validateProduct->fails()){
            return response()->json([
                'status' => false,
                'message' => "validation error",
                'data' => $validateProduct->errors(),
            ], 422);
        }
        
       

        $product = Product::find($request->product_id);
    if (!$product) {
        return response()->json([
            'status' => false,
            'message' => 'Product not found',
        ], 404);
    }

    $product->delete();

    return response()->json([
        'status' => true,
        'message' => 'Product deleted successfully',
    ], 200);

    }

    //Excel
    public function importProducts(Request $request)
    {
  
        $request->validate([
           'file' => 'required|file|mimes:xlsx,csv',
        ]);

        dd($request->file('file'));

        Excel::import(new ProductsImport, $request->file('file'));

        return response()->json(['message' => 'Products imported successfully.'],200);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'category_id' => $this->category_id,
            'price' => $this->price,
        ];
    }


}
