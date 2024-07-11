<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use DataTables;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $data = Role::select('*')->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addColumn('checkbox', '<input type="checkbox" name="role_id[]" value="{{$id}}" />')
                ->addColumn('permissions', '<a href="{{route("edit-role-permission",["id"=>$id])}}" class="btn btn-success btn-circle"><i class="fas fa-tasks"></i></a>')
                ->addColumn('edit', '<button onclick="editRole({{$id}})" class="edit-btn btn btn-primary btn-circle"><i class="fas fa-edit"></i></button>')
                ->addColumn('delete', '<a href="{{route("role-delete",["id"=>$id])}}" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a>')
                ->rawColumns(['checkbox', 'permissions', 'edit', 'delete'])
                ->make(true);
        }
        return view('role.list');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required|string|unique:roles,name'
        ]);

        //new record
        if($request->id == "0")
        {
            Role::create(['name' => $request->title]);
        }
        else{
            $role=Role::findOrFail($request->id);
            $role->update(['name' => $request->title]);
        }
        

        return redirect()->back();
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $role = Role::findOrFail($id);
            return response()->json($role, 200); // Return a JSON response with a 200 status code
        } catch (\Exception $e) {

            $errorMessage = 'Role not found.';
            return response()->json(['error' => $errorMessage], 404); // Return a JSON response with a 404 status code
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $id)
    {
        $id->delete();
        return redirect()->back(); 
    }



    public function deleteSelected(Request $request)
    {
        $selectedIds = $request->selectedIds;

        // Delete the Role
        Role::whereIn('id', $selectedIds)->delete();

        return response()->json(['status' => 'success','message' => 'Selected records deleted successfully']);
    }


    public function permissionEdit(string $id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::get();
        $role_permissions = $role->permissions()->pluck('permission_id')->toArray();

        $data=compact('permissions','role','role_permissions');
        return view('role.edit_permission')->with($data);
    }


    public function permissionUpdate(Request $request)
    {
        $request->validate([
            'role_id' => 'required',
        ]);

        if($request->permissions)
        {
            $role = Role::findOrFail($request->role_id);
            $role->syncPermissions($request->permissions);
            return redirect()->route('roles')->with('success','Roles Permission has been updated');
        }

        return redirect()->route('roles');

    }

}
