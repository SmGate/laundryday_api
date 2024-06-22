<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{


    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'arabic_name' => 'required|string|max:255',
            'image' => 'nullable|string',
            'service_id' => 'required|integer|exists:services,id', // Ensures service_id exists
        ]);

        if ($validator->fails()) {
            return response()->json(
             [ "errors"=> $validator->errors()]
            , 403);
        }

    $base64Image = $request->input('image');
    $path = null;
    if ($base64Image) {
        
        $image = base64_decode($base64Image);
        $imageName = time() . ".png";
        $imagePath = public_path('/storage/categories/' . $imageName);
        file_put_contents($imagePath, $image);
        $path = 'categories/' . $imageName;
        $request['image'] = $path;

    }

        $category = Category::create($request->all());

        $message = 'Category created successfully!'; // Customize this message

        return response()->json([
            'message' => $message,
            'data' => $category,
        ], 201); //


    }


    ////////  GET ALL CATEGORY
    public function getAllCategory()
    {
        // Fetch all categories
        $categories = Category::with('service')->get();

        // Return a JSON response
        return response()->json([
            'message' => 'Categories retrieved successfully!',
            'data' => $categories
        ], 200);
    }


    //////////  UPDATE CATEGORY
    public function updateCategory(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'arabic_name' => 'required|string|',
            'image' => 'nullable|string',
            'service_id' => 'required|integer|exists:services,id',
            'id' => 'required|exists:categories,id',
        ]);

         if ($validator->fails()) {
            return response()->json(
             [ "errors"=> $validator->errors()]
            , 403);
        }


 $category = Category::findOrFail($request->id);


    $base64Image = $request->input('image');
    $path =null;
    if ($base64Image) {
        
        Storage::delete('public/' . $category->image);
        $image = base64_decode($base64Image);
        $imageName = time() . ".png";
        $imagePath = public_path('/storage/categories/' . $imageName);
        file_put_contents($imagePath, $image);
        $path = 'categories/' . $imageName;
        $request['image'] = $path;

    }

       





        $category->update([
            'name' => $request->get('name', $category->name),
            'arabic_name' => $request->get('arabic_name', $category->arabic_name),
            'image' => $path,
            'service_id' => $request->get('service_id', $category->service_id),
        ]);

        return response()->json([
            'message' => 'Category updated successfully!',
            'data' => $category
        ], 200);
    }



    public function deleteCategory($id)
    {

        $category = Category::findOrFail($id);

        Storage::delete('public/' . $category->image);


        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully!'
        ], 200);
    }
}
