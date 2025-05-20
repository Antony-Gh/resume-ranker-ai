<?php

namespace App\Http\Controllers;

use App\Models\SaverUser;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class SaverUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['store']);
        $this->middleware('can:manage-users')->only(['index', 'destroy', 'forceDelete', 'restore']);
    }

    public function index()
    {
        $users = SaverUser::with(['passwords', 'loginHistory' => function($query) {
            $query->latest()->limit(5);
        }])->paginate(15);

        return response()->json($users);
    }

    public function show(SaverUser $user)
    {
        $this->authorize('view', $user);

        return response()->json($user->load(['passwords', 'loginHistory']));
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['master_password'] = Hash::make($validated['master_password']);

        $user = SaverUser::create($validated);

        return response()->json($user, 201);
    }

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

    public function destroy(SaverUser $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json(null, 204);
    }

    public function forceDelete($id)
    {
        $user = SaverUser::withTrashed()->findOrFail($id);

        $this->authorize('forceDelete', $user);

        $user->forceDelete();

        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $user = SaverUser::withTrashed()->findOrFail($id);

        $this->authorize('restore', $user);

        $user->restore();

        return response()->json($user);
    }
}
