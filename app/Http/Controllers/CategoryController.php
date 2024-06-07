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
            'image' => 'nullable|file', // Optional image field
            'service_id' => 'required|integer|exists:services,id', // Ensures service_id exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $image = $request->file('category_image');
        $path = '';
        if ($image != null) {
            $imageName = time() . "." . $image->extension();
            $image->move(public_path('/storage/category'), $imageName);
            $path = 'category/' . $imageName;
        }
        $request['image'] = $path;

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
    public function updateCategory(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'arabic_name' => 'required|string|',
            'image' => 'nullable|file', // Optional image field
            'service_id' => 'required|integer|exists:services,id', // Ensures service_id exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found!'
            ], 404);
        }


        $image = $request->file('category_image');
        $path = $category->image;

        if ($image != null) {
            $imageName = time() . "." . $image->extension();
            $image->move(public_path('/storage/category'), $imageName);
            $path = 'category/' . $imageName;
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


    //////// DELETE IMAGE

    public function deleteCategory($id)
    {

        $category = Category::find($id);


        if (!$category) {
            return response()->json([
                'message' => 'Category not found!'
            ], 404);
        }


        $file_path = public_path($category->image);


        if (File::exists($file_path)) {

            File::delete($file_path);
        }

        // Delete the category
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully!'
        ], 200);
    }
}
