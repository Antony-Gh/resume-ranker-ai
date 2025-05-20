<?php

namespace App\Http\Controllers;

use App\Models\SaverCategory;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\JsonResponse;

class SaverCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = SaverCategory::where('user_id', auth()->id())
            ->withCount('passwords')
            ->latest()
            ->get();

        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = SaverCategory::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'icon' => $request->icon,
            'color' => $request->color,
        ]);

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SaverCategory $category): JsonResponse
    {
        $this->authorize('view', $category);

        return response()->json($category->load('passwords'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, SaverCategory $category): JsonResponse
    {
        $this->authorize('update', $category);

        $category->update($request->validated());

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaverCategory $category): JsonResponse
    {
        $this->authorize('delete', $category);

        // Check if category is empty before deleting
        if ($category->passwords()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with passwords. Move or delete passwords first.'
            ], 422);
        }

        $category->delete();

        return response()->json(null, 204);
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restore($id): JsonResponse
    {
        $category = SaverCategory::withTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $this->authorize('restore', $category);

        $category->restore();

        return response()->json($category);
    }

    /**
     * Permanently delete a category.
     */
    public function forceDelete($id): JsonResponse
    {
        $category = SaverCategory::withTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $this->authorize('forceDelete', $category);

        // Detach all passwords first
        $category->passwords()->update(['category_id' => null]);

        $category->forceDelete();

        return response()->json(null, 204);
    }
}
