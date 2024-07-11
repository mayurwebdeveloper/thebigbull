<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Staff;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Staff::select('*')->orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addColumn('checkbox', '<input type="checkbox" name="staff_id[]" value="{{$id}}" />')
                ->addColumn('view', '<a href="{{route("view-staff",["id"=>$id])}}" class="btn btn-primary btn-circle"><i class="fas fa-eye"></i></a>')
                ->addColumn('edit', '<a href="{{route("edit-staff-form",["id"=>$id])}}" class="btn btn-info btn-circle"><i class="fas fa-edit"></i></a>')
                ->addColumn('delete', '<a href="{{route("staff-delete",["id"=>$id])}}" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a>')
                ->rawColumns(['checkbox', 'view', 'edit', 'delete'])
                ->make(true);
        }
        return view('staff.list');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $designations = Designation::where('status','1')->get();
        $data = compact('designations');
        return view('staff.create')->with($data);
    }


    private function generateUniqueId()
    {
        do {
            // $uniqueId = mt_rand(100000, 999999);
            $uniqueId = Str::random(6);
        } while (Staff::where('unique_id', $uniqueId)->exists());

        return $uniqueId;
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'fname'=>'required',
                'lname'=>'required',
                'dob'=>'required',
                'mo'=>'required',
                'email'=>'required|unique:staff,email',
                'status'=>'required',
                'designation'=>'required',
                'join_date'=>'required',
                'salary'=>'required',
                'address'=>'required',
                'password'=>'required',
            ]
        );

        // Generate a unique 6-digit numeric ID
        $uniqueId = $this->generateUniqueId();
         
        $staff=new Staff();
        $staff->unique_id = $uniqueId;
        $staff->fname = $request['fname'];
        $staff->lname = $request['lname'];
        $staff->DOB = $request['dob'];
        $staff->mo = $request['mo'];
        $staff->status = $request['status'];
        $staff->designation_id = $request['designation'];
        $staff->join_date = $request['join_date'];
        $staff->salary = $request['salary'];
        $staff->email = $request['email'];
        $staff->password = $request['password'];
        $staff->address = $request['address'];


        // store profile photo
        $staffPhotoPath = 'images/staff/photos';
        if (!file_exists($staffPhotoPath)) {
            mkdir($staffPhotoPath, 0777, true);
        }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_image_name = time() . mt_rand(1, 2000) . '.' . $photo->extension();
            $Folder = public_path($staffPhotoPath);
            $photo->move($Folder, $photo_image_name);
            $staff->photo = $photo_image_name;
        }

        if($staff->save())
        {
            return redirect()->route('staff')->with('success','Staff has been added');
        }

        return redirect()->route('staff')->with('error','something went wrong!!!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff=Staff::findOrFail($id);
        $designations = Designation::where('status','1')->get();
        $data=compact('staff','designations');
        return view('staff.edit')->with($data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate(
            [
                'staff_id'=>'required',
                'fname'=>'required',
                'mname'=>'required',
                'lname'=>'required',
                'dob'=>'required',
                'mo'=>'required',
                'status'=>'required',
                'designation'=>'required',
                'join_date'=>'required',
                'salary'=>'required',
                'bank'=>'required',
                'ac_no'=>'required',
                'ifsc_no'=>'required',
                'licence_no'=>'required',
                'licence_exp_date'=>'required',
                'bus'=>'required',
                'route'=>'required',
                'address'=>'required',
            ]
        );


        $staff=Staff::findOrFail($request->staff_id);
        $staff->fname = $request['fname'];
        $staff->mname = $request['mname'];
        $staff->lname = $request['lname'];
        $staff->DOB = $request['dob'];
        $staff->mo = $request['mo'];
        $staff->status = $request['status'];
        $staff->designation_id = $request['designation'];
        $staff->join_date = $request['join_date'];
        $staff->salary = $request['salary'];
        $staff->bank = $request['bank'];
        $staff->ac_no = $request['ac_no'];
        $staff->ifsc_no = $request['ifsc_no'];
        $staff->licence_no = $request['licence_no'];
        $staff->licence_exp_date = $request['licence_exp_date'];
        $staff->bus_id = $request['bus'];
        $staff->route_id = $request['route'];
        $staff->address = $request['address'];


        // store profile photo
        $staffPhotoPath = 'images/staff/photos';
        if (!file_exists($staffPhotoPath)) {
            mkdir($staffPhotoPath, 0777, true);
        }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_image_name = time() . mt_rand(1, 2000) . '.' . $photo->extension();
            $Folder = public_path($staffPhotoPath);
            $photo->move($Folder, $photo_image_name);
            $staff->photo = $photo_image_name;
        }


        if($staff->save())
        {
            $lastId = $staff->id;

             // delete Documents
            StaffDocument::where('staff_id',$lastId)->delete();

            //insert old Documents data
            if($request->old_documents != null)
            {
                $p=0;
                foreach ($request->old_documents as $old_document) {
                    $doc = new StaffDocument();
                    $doc->staff_id = $lastId;    
                    $doc->document = $old_document; 
                    $doc->title = $request->old_document_titles[$p];  
                    $doc->save();
                    $p++;
                }
            }

            // add documents
            $staffDocumentPath = 'images/staff/documents';
            if (!file_exists($staffDocumentPath)) {
                mkdir($staffDocumentPath, 0777, true);
            }
       

            if ($request->hasFile('documents')) {
                $i=0;
                foreach ($request->file('documents') as $document) {
                    $document_image_name = time() . mt_rand(1, 2000) . '.' . $document->extension();
                    $Folder = public_path($staffDocumentPath);
                    $document->move($Folder, $document_image_name);

                    //insert documents in database
                    $staffDocument = new StaffDocument();
                    $staffDocument->staff_id = $lastId;    
                    $staffDocument->document = $document_image_name;   
                    $staffDocument->title = $request->document_titles[$i];
                    $staffDocument->save();
                    $i++;
                }
            }


            return redirect()->route('staff')->with('success','Staff has been updated');
        }

        return redirect()->route('staff')->with('error','something went wrong!!!');

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $id)
    {
        $id->staffDocuments()->delete();
        $id->delete();
        return redirect()->back(); 
    }


    public function deleteSelected(Request $request)
    {
        $selectedIds = $request->selectedIds;

        // Find the posts by their IDs
        $staffs = Staff::whereIn('id', $selectedIds)->get();

        // Loop through the buses and delete associated records
        foreach ($staffs as $staff) {
            $staff->staffDocuments()->delete();
        }

        // Delete the posts
        Staff::whereIn('id', $selectedIds)->delete();

        return response()->json(['message' => 'Selected records deleted successfully']);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff=Staff::findOrFail($id);
        $staffDocuments = StaffDocument::where("staff_id",$staff->id)->get();
        $documentCount = count($staffDocuments);
        $designations = Designation::where('status','1')->get();
        $buses = Bus::where('status','1')->get();
        $bus_routes = BusRoute::where('status','1')->get();
        $data=compact('staff','staffDocuments','documentCount','designations','buses','bus_routes');
        return view('staff.show')->with($data);
    }

}

