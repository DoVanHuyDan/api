<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Task;
use App\Models\Department;
use App\Models\User;
use App\Models\File;


class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $pagination = File::where('tenant_id', $user['tenant_id'])->with('creator', 'category')->paginate($request->per_page ?? 100);
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
    
    public function store(Request $request)
    {
        try {
            $data = $request->toArray();
            $user = Auth::user();
            if ($request->has('newFiles')) {
                $file = $request->file('newFiles');
                    $path = $file->store('files', 'public');
                    $originName = $file->getClientOriginalName();
                    $size = $file->getClientsize();
                    $attachment = File::create([
                        'name' => $originName,
                        'path' => $path,
                        'size' => $size,
                        'user_id' => $user['id'],
                        'category_id' => $data['category_id'],
                        'tenant_id' => $user['tenant_id'],
                    ]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function upload($files, $document)
    {
        foreach ($files as $file) {
            $path = $file->store('files', 'public');
            $originName = $file->getClientOriginalName();
            $size = $file->getClientsize();
            $attachment = File::create([
                'name' => $originName,
                'path' => $path,
                'size' => $size
            ]);
            $document->files()->save($attachment);
        }
    }

   

    public function syncFiles($attachment_relations, $document)
    {
        $oldIds = $document->files()->pluck('id')->all();
        $newIds = [];
        foreach ($attachment_relations as $index => $file) {
            array_push($newIds, $file['id']);
        }
        $deleteIds = [];
        foreach ($oldIds as $oldId) {
            if (!in_array($oldId, $newIds)) {
                $deleteIds[] = $oldId;
            }
        }
        $paths = [];
        foreach ($deleteIds as $id) {
            $attachment = File::where('id', $id)->first();
            if ($attachment != null) {
                array_push($paths, $attachment->path);
                $attachment->delete();
            }
        }
        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            File::where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function search(Request $request)
    {
        $data = $request->toArray();
        $query = File::query()->with('creator');
        if (array_key_exists('category_id', $data)) {
            $query = $query->where('category_id','LIKE','%'.$data['category_id'].'%');
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


    public function download($id){
        $file = File::where('id', $id)->first();
        // $path = $file->path;
        $path = Storage::disk('public')->path($file->path);
        $headers = array(
            'Content-Type: image/png',
          );
       return Response::download($path, $file->name, $headers);
        // return Storage::disk('public')->download($path,$file->name);
    }
}
