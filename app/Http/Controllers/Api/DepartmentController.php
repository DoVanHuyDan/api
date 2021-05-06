<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Models\Department;


class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $pagination = Department::paginate($request->per_page ?? 20);
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

    public function show(Request $request, $id)
    {
        $model = Department::where('id', $id)->first();
        if (!$model) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $model,
        ]);
    }

    public function store(CategoryStoreRequest $request)
    {
        try {
            $model = new Department();
            $data = $request->only($model->getFillable());
            $model->fill($data);
            $model->save();
            return response()->json(['success' => true, 'data' => $model]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function update(DepartmentUpdateRequest $request, $id)
    {
        try {
            $data = $request->toArray();
            $model = Department::where('id', $id)->first();
            if (!$model) {
                return response()->json(['error' => 'Not found'], 404);
            }
            $model->fill($data);
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
            Department::where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
