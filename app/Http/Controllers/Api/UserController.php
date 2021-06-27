<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $pagination = User::paginate($request->per_page ?? 100);
        return response()->json([
            'success' => true,
            'data' => $pagination->items(),
            'paging' => [
                'current_page' => $pagination->currentPage(),
                'per_page' => $pagination->perPage(),
                'total' => $pagination->total(),
            ]
        ]);
    }

    public function getMyUser(Request $request)
    {
        $id = Auth::user()->tenant_id;
        $model = User::where('tenant_id', $id)->paginate($request->per_page ?? 100);;
        return response()->json([
            'success' => true,
            'data' => $model->items(),
            'paging' => [
                'current_page' => $model->currentPage(),
                'per_page' => $model->perPage(),
                'total' => $model->total(),
            ]
        ]);
    }

    public function show(Request $request, $id)
    {
        $model = User::where('id', $id)->first();
        if (!$model) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $model,
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $model = new User();
            $data = $request->only($model->getFillable());
            $data['password'] = Hash::make($data['password']);
            $model->fill($data);
            $model->save();
            return response()->json(['success' => true, 'data' => $model]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $data = $request->toArray();
            $model = User::where('id', $id)->first();
            if (!$model) {
                return response()->json(['error' => 'Not found'], 404);
            }
            $model->fill($data);
            if (isset($data['password'])) {
                $model->password = Hash::make($data['password']);
            }
            $model->update();
            return response()->json([
                'success' => true,
                'data' => $model
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'error' => 'Server error'
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            User::where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function search(Request $request)
    {
        $data = $request->toArray();
        $query = User::query();
        if (array_key_exists('name', $data)) {
            $query = $query->where('name','LIKE','%'.$data['name'].'%');
        }
        if (array_key_exists('department_id', $data)) {
            $query = $query->where('department_id', intval($data['department_id']));
        }
        $result = $query->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $result->items(),
            'paging' => [
                'current_page' => $result->currentPage(),
                'per_page' => $result->perPage(),
                'total' => $result->total(),
            ]
        ]);
    }

    public function updateProfile(UserUpdateRequest $request)
    {
        try {
            $model = User::where('id', Auth::id())->first();
            if (!$model) {
                return response()->json(['error' => 'Not found'], 404);
            }
            $data = $request->toArray();
            $model->fill($data);
            if (isset($data['password'])) {
                $model->password = Hash::make($data['password']);
            }
            $model->update();
            return response()->json([
                'success' => true,
                'data' => $model
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'error' => 'Server error'
            ], 500);
        }
    }
}
