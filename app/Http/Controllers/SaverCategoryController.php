<?php

namespace App\Http\Controllers;

use App\Models\SaverCategory;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="API Endpoints for Managing Saver Categories"
 * )
 */
class SaverCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="List user's categories",
     *     tags={"Categories"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="A list of the authenticated user's categories.",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/SaverCategory"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
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
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category data",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Work"),
     *             @OA\Property(property="icon", type="string", nullable=true, example="briefcase-icon"),
     *             @OA\Property(property="color", type="string", nullable=true, example="#FF0000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully.",
     *         @OA\JsonContent(ref="#/components/schemas/SaverCategory")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
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
     * @OA\Get(
     *     path="/api/categories/{category}",
     *     summary="Get a specific category",
     *     tags={"Categories"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="ID of the category",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SaverCategory")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function show(SaverCategory $category): JsonResponse
    {
        $this->authorize('view', $category);

        return response()->json($category->load('passwords'));
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{category}",
     *     summary="Update a category",
     *     tags={"Categories"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="ID of the category to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category data to update",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Personal Updated"),
     *             @OA\Property(property="icon", type="string", nullable=true, example="user-icon"),
     *             @OA\Property(property="color", type="string", nullable=true, example="#00FF00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully.",
     *         @OA\JsonContent(ref="#/components/schemas/SaverCategory")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Category not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateCategoryRequest $request, SaverCategory $category): JsonResponse
    {
        $this->authorize('update', $category);

        $category->update($request->validated());

        return response()->json($category);
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{category}",
     *     summary="Delete a category",
     *     tags={"Categories"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="ID of the category to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Category deleted successfully"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Category not found"),
     *     @OA\Response(response=422, description="Cannot delete category with passwords")
     * )
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
