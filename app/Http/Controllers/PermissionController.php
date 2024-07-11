<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use DataTables;

class PermissionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::select('*')->orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addColumn('checkbox', '<input type="checkbox" name="permission_id[]" value="{{$id}}" />')
                ->addColumn('edit', '<button onclick="editPermission({{$id}})" class="edit-btn btn btn-primary btn-circle"><i class="fas fa-edit"></i></button>')
                ->addColumn('delete', '<a href="{{route("permission-delete",["id"=>$id])}}" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a>')
                ->rawColumns(['checkbox', 'edit', 'delete'])
                ->make(true);
        }
        return view('permission.list');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required|string|unique:permissions,name'
        ]);

        //new record
        if($request->id == "0")
        {
            Permission::create(['name' => $request->title]);
        }
        else{
            $permission=Permission::findOrFail($request->id);
            $permission->update(['name' => $request->title]);
        }
        

        return redirect()->back();
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            return response()->json($permission, 200); // Return a JSON response with a 200 status code
        } catch (\Exception $e) {

            $errorMessage = 'Permission not found.';
            return response()->json(['error' => $errorMessage], 404); // Return a JSON response with a 404 status code
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $id)
    {
        $id->delete();
        return redirect()->back(); 
    }


    public function deleteSelected(Request $request)
    {
        $selectedIds = $request->selectedIds;

        // Delete the Permission
        Permission::whereIn('id', $selectedIds)->delete();

        return response()->json(['status' => 'success','message' => 'Selected records deleted successfully']);
    }


}
