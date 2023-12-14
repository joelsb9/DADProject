<?php

namespace App\Http\Controllers\api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        // Retrieve all default categories
        $categories = Category::all();

        return CategoryResource::collection($categories)->paginate(10);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function store(StoreCategoryRequest $request)
    {
        $dataToSave = $request->validated();

        // Create a new category
        $category = Category::create($dataToSave);

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $dataToSave = $request->validated();

        // Update the category
        $category->update($dataToSave);

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        // Soft delete the category
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
    public function restore($categoryId)
    {
        // Restore the category
        Category::withTrashed()->find($categoryId)->restore();

        return response()->json(['message' => 'Category restored successfully']);
    }
}
