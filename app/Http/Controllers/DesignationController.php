<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;
use DataTables;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Designation::select('*')->orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addColumn('checkbox', '<input type="checkbox" name="designation_id[]" value="{{$id}}" />')
                ->addColumn('edit', '<button onclick="editDesignation({{$id}})" class="edit-btn btn btn-info btn-circle"><i class="fas fa-edit"></i></button>')
                ->addColumn('delete', '<a href="{{route("designation-delete",["id"=>$id])}}" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a>')
                ->rawColumns(['checkbox', 'edit', 'delete'])
                ->make(true);
        }
        return view('designation.list');
    }


    public function deleteSelected(Request $request)
    {
        $selectedIds = $request->selectedIds;

        $designations = Designation::whereIn('id', $selectedIds)->get();

        foreach ($designations as $designation) {

            if ($designation->staff()->exists()) {
                return response()->json(['status' => 'error','message' => 'Cannot delete because some Designations are associated with staff records.']);
            }
        }

        // Delete the posts
        Designation::whereIn('id', $selectedIds)->delete();

        return response()->json(['status' => 'error','message' => 'Selected records deleted successfully']);
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'id'=>'required',
                'title'=>'required',
                'status'=>'required',
            ]
        );

        //new record
        if($request->id == "0")
            $designation=new Designation();
        else
            $designation=Designation::findOrFail($request->id);

        $designation->title=$request['title'];
        $designation->status=$request['status'];
        $designation->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $designation = Designation::findOrFail($id);
            return response()->json($designation, 200); // Return a JSON response with a 200 status code
        } catch (\Exception $e) {

            $errorMessage = 'Bus Designation not found.';
            return response()->json(['error' => $errorMessage], 404); // Return a JSON response with a 404 status code
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $id)
    {
        // Check if there are related Staff records
        if ($id->staff()->exists()) {
            // You can handle this situation as needed, e.g., show an error message.
            return redirect()->back()->with('error', 'Cannot delete this Designation because it is associated with staff records.');
        }

        $id->delete();
        return redirect()->back(); 
    }
}
