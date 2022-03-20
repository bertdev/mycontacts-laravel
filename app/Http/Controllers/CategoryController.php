<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all()->categories();
        return response($categories, 200);
    }

    public function show(string $id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return response(['error' => 'Category not found'], 404);
        }
        return response($category, 200);
    }

    public function create(Request $request)
    {
        $validation = validator($request->only(['name']), [
            'name' => 'required|unique:categories,name'
        ]);
        if ($validation->fails()) {
            return response([
                'error' => $validation->errors()->first()
            ], 400);
        }
        $newCategory = Category::create([
            'id' => Str::uuid(),
            'name' => $request->name
        ]);
        return response($newCategory, 201);
    }

    public function update(string $id, Request $request)
    {
        $validation = validator($request->only(['name']), [
            'name' => 'required|unique:categories,name'
        ]);
        if ($validation->fails()) {
            return response([
                'error' => $validation->errors()->first()
            ], 400);
        }
        $category = Category::find($id);
        if (is_null($category)) {
            return response(['error' => 'Category not found'], 404);
        }
        $category->name = $request->name;
        $category->save();
        return response(null, 204);
    }

    public function delete(string $id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return response(['error' => 'Category not found'], 404);
        }
        Category::destroy($id);
        return response(null, 204);
    }
}
