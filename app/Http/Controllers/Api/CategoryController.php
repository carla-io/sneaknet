<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::all();
        return response()->json([
            'status' => true,
            'message' => "Category Successfully",
            'data' => $categories,
        ], 200);
    }

    //Add Product
    public function create(Request $request){

        $validateCategory = Validator::make($request->all(),[
            'name' => 'required|unique:categories,name',
            // 'description' => isset($request->description) ? $request->description : '',
        ]);

        if($validateCategory->fails()){
            return response()->json([
                'status' => false,
                'message' => "validation error",
                'data' => $validateCategory->errors(),
            ], 422);
        }

        $inputData = array(
            'name' => $request->name,
            'description' => isset($request->description) ? $request->description : '',
        );

        $categories = category::create($inputData);

        return response()->json([
            'status' => true,
            'message' => "Category Added Successfully",
            'data' => $categories,
        ], 200);

    }

     //Update Category
     public function update(Request $request){
        $validateCategory = Validator::make($request->all(),[
            'category_id' => 'required|exists:products,id',
            'category_name' => 'required',
        ]);

        if($validateCategory->fails()){
            return response()->json([
                'status' => false,
                'message' => "validation error",
                'data' => $validateCategory->errors(),
            ], 422);
        }

        $categories = Category::find($request->category_id);
        $categories->category_name = $request->category_name;
        $categories->description = isset($request->description) ? $request->description: '';
        $categories->save();

        return response()->json([
            'status' => true,
            'message' => "Category Updated Successfully",
            'data' => $categories,
        ], 200);
    }

    // Delete Category
    public function delete(Request $request){
        $validateCategory = Validator::make($request->all(),[
            'category_id' => 'required|exists:categories,id',
        ]);

        if($validateCategory->fails()){
            return response()->json([
                'status' => false,
                'message' => "validation error",
                'data' => $validateCategory->errors(),
            ], 422);
        }
        
        // $products = Product::find($request->product_id)->delete();

        $category = Category::find($request->category_id);
    if (!$category) {
        return response()->json([
            'status' => false,
            'message' => 'Category not found',
        ], 404);
    }

    $category->delete();

    return response()->json([
        'status' => true,
        'message' => 'Category deleted successfully',
    ], 200);

    }
}
