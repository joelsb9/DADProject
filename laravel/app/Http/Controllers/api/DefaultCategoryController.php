<?php

namespace App\Http\Controllers\Api;

use App\Models\DefaultCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultCategoryResource;
use App\Http\Requests\StoreDefaultCategoryRequest;
use App\Http\Requests\UpdateDefaultCategoryRequest;

class DefaultCategoryController extends Controller
{
    public function index()
    {
        // Retrieve all default categories
        $defaultCategories = DefaultCategory::all();

        return DefaultCategoryResource::collection($defaultCategories);
    }

    public function show(DefaultCategory $defaultCategory)
    {
        return new DefaultCategoryResource($defaultCategory);
    }

    public function store(StoreDefaultCategoryRequest $request)
    {
        $dataToSave = $request->validated();

        // Create a new default category
        $defaultCategory = DefaultCategory::create($dataToSave);

        return new DefaultCategoryResource($defaultCategory);
    }

    public function update(UpdateDefaultCategoryRequest $request, DefaultCategory $defaultCategory)
    {
        $dataToSave = $request->validated();

        // Update the default category
        $defaultCategory->update($dataToSave);

        return new DefaultCategoryResource($defaultCategory);
    }

    public function destroy(DefaultCategory $defaultCategory)
    {
        // Soft delete the default category
        $defaultCategory->delete();

        return response()->json(['message' => 'Default category deleted successfully']);
    }

    public function restore($defaultCategoryId)
    {
        // Restore the default category
        DefaultCategory::withTrashed()->find($defaultCategoryId)->restore();

        return response()->json(['message' => 'Default category restored successfully']);
    }
}
