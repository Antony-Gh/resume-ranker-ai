<?php

namespace App\Http\Controllers;

use App\Models\SaverUser;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Users (Admin)",
 *     description="API Endpoints for Admin User Management of SaverUsers"
 * )
 * @OA\Tag(
 *     name="User Profile",
 *     description="API Endpoints for the authenticated SaverUser to manage their own profile"
 * )
 */
class SaverUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['store']);
        $this->middleware('can:manage-users')->only(['index', 'destroy', 'forceDelete', 'restore']);
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="List all SaverUsers (Admin)",
     *     tags={"Users (Admin)"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="page", in="query", description="Page number for pagination", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Paginated list of users", @OA\JsonContent(type="object",
     *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SaverUser")),
     *         @OA\Property(property="links", type="object", description="Pagination links"),
     *         @OA\Property(property="meta", type="object", description="Pagination meta")
     *     )),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden (User is not an admin)")
     * )
     */
    public function index()
    {
        $users = SaverUser::with(['passwords', 'loginHistory' => function($query) {
            $query->latest()->limit(5);
        }])->paginate(15);

        return response()->json($users);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{user}",
     *     summary="Get a specific SaverUser (Admin)",
     *     tags={"Users (Admin)"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="user", in="path", required=true, description="ID of the SaverUser", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User details", @OA\JsonContent(ref="#/components/schemas/SaverUser")),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden (Action not allowed or user is not an admin for this specific user view if policy is strict)"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function show(SaverUser $user)
    {
        $this->authorize('view', $user);

        return response()->json($user->load(['passwords', 'loginHistory']));
    }

    // store() method is not directly used by admin routes or /api/user, so skipping annotation for now.
    // If it were for public registration, it would be in AuthController or similar.
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['master_password'] = Hash::make($validated['master_password']);

        $user = SaverUser::create($validated);

        return response()->json($user, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{user}",
     *     summary="Update a specific SaverUser (Admin)",
     *     tags={"Users (Admin)"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="user", in="path", required=true, description="ID of the SaverUser to update", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, description="User data to update. Note: password fields are optional.",
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string", example="updateduser"),
     *             @OA\Property(property="email", type="string", format="email", example="updated@example.com"),
     *             @OA\Property(property="password", type="string", format="password", nullable=true, description="New password (if changing)"),
     *             @OA\Property(property="master_password", type="string", format="password", nullable=true, description="New master password (if changing)")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User updated successfully", @OA\JsonContent(ref="#/components/schemas/SaverUser")),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateUserRequest $request, SaverUser $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        if (isset($validated['master_password'])) {
            $validated['master_password'] = Hash::make($validated['master_password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{user}",
     *     summary="Soft delete a specific SaverUser (Admin)",
     *     tags={"Users (Admin)"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="user", in="path", required=true, description="ID of the SaverUser to delete", @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="User soft deleted successfully"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function destroy(SaverUser $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/force/{user_id}",
     *     summary="Permanently delete a specific SaverUser (Admin)",
     *     tags={"Users (Admin)"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="user_id", in="path", required=true, description="ID of the SaverUser to force delete", @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="User permanently deleted successfully"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function forceDelete($id) // Route: /api/users/force/{user}
    {
        $user = SaverUser::withTrashed()->findOrFail($id);

        $this->authorize('forceDelete', $user);

        $user->forceDelete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Patch(
     *     path="/api/users/restore/{user_id}",
     *     summary="Restore a soft-deleted SaverUser (Admin)",
     *     tags={"Users (Admin)"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="user_id", in="path", required=true, description="ID of the SaverUser to restore", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User restored successfully", @OA\JsonContent(ref="#/components/schemas/SaverUser")),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function restore($id) // Route: /api/users/restore/{user}
    {
        $user = SaverUser::withTrashed()->findOrFail($id);

        $this->authorize('restore', $user);

        $user->restore();

        return response()->json($user);
    }
}
