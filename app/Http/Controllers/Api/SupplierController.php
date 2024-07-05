<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(Request $request){
        $supplier = Category::all();
        return response()->json([
            'status' => true,
            'message' => "Supplier Listed Successfully",
            'data' => $supplier,
        ], 200);
    }

     //Add Supplier
     public function create(Request $request){

        $validateSupplier = Validator::make($request->all(),[
            'supplier_name' => 'required|unique:suppliers,name',
            'contact_name' => 'required|string',
            'email' => 'required|string',
            'supplier_phone' => 'required|string',
            'address' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($validateSupplier->fails()){

            // Log::error('Validation errors:', $validateSupplier->errors()->toArray());
            
            return response()->json([
                'status' => false,
                'message' => "validation error",
                'data' => $validateSupplier->errors(),
            ], 422);
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
        }

        $inputData = array(
            'supplier_name' => $request->supplier_name,
            'contact_name' => $request->contact_name,
            'email' => $request->email,
            'supplier_phone' => $request->supplier_phone,
            'address' => $request->address,
            'image' => $imageName,
        );

        $suppliers = Category::create($inputData);

        return response()->json([
            'status' => true,
            'message' => "Category Added Successfully",
            'data' => $suppliers,
        ], 200);

    }
}
