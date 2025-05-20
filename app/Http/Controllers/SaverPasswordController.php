<?php

namespace App\Http\Controllers;

use App\Models\SaverPassword;
use App\Http\Requests\StorePasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;

class SaverPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $passwords = SaverPassword::where('user_id', auth()->id())
            ->with('category')
            ->latest()
            ->paginate(20);

        return response()->json($passwords);
    }

    public function show(SaverPassword $password)
    {
        $this->authorize('view', $password);

        return response()->json($password->load('category'));
    }

    public function store(StorePasswordRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $validated['encrypted_password'] = Crypt::encrypt($validated['password']);

        $password = SaverPassword::create($validated);

        return response()->json($password, 201);
    }

    public function update(UpdatePasswordRequest $request, SaverPassword $password)
    {
        $this->authorize('update', $password);

        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['encrypted_password'] = Crypt::encrypt($validated['password']);
            unset($validated['password']);
        }

        $password->update($validated);

        return response()->json($password);
    }

    public function destroy(SaverPassword $password)
    {
        $this->authorize('delete', $password);

        $password->delete();

        return response()->json(null, 204);
    }

    public function forceDelete($id)
    {
        $password = SaverPassword::withTrashed()->findOrFail($id);

        $this->authorize('forceDelete', $password);

        $password->forceDelete();

        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $password = SaverPassword::withTrashed()->findOrFail($id);

        $this->authorize('restore', $password);

        $password->restore();

        return response()->json($password);
    }
}
