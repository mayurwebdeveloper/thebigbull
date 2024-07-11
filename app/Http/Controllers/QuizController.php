<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Quiz;
use Illuminate\Http\Request;
use DataTables;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Quiz::select('*')->orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addColumn('checkbox', '<input type="checkbox" name="quiz_id[]" value="{{$id}}" />')
                ->addColumn('view', '<a href="{{route("view-quiz",["id"=>$id])}}" class="btn btn-primary btn-circle"><i class="fas fa-eye"></i></a>')
                ->addColumn('edit', '<a href="{{route("edit-quiz-form",["id"=>$id])}}" class="btn btn-info btn-circle"><i class="fas fa-edit"></i></a>')
                ->addColumn('delete', '<a href="{{route("quiz-delete",["id"=>$id])}}" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a>')
                ->rawColumns(['checkbox', 'view', 'edit', 'delete'])
                ->make(true);
        }
        return view('quiz.list');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $designations = Designation::where('status','1')->get();
        $data = compact('designations');
        return view('quiz.create')->with($data);
    }



    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     $request->validate(
    //         [
    //             'fname'=>'required',
    //             'lname'=>'required',
    //             'dob'=>'required',
    //             'mo'=>'required',
    //             'email'=>'required|unique:staff,email',
    //             'status'=>'required',
    //             'designation'=>'required',
    //             'join_date'=>'required',
    //             'salary'=>'required',
    //             'address'=>'required',
    //             'password'=>'required',
    //         ]
    //     );


    //     $quiz=new Staff();
    //     $quiz->fname = $request['fname'];
    //     $quiz->lname = $request['lname'];
    //     $quiz->DOB = $request['dob'];
    //     $quiz->mo = $request['mo'];
    //     $quiz->status = $request['status'];
    //     $quiz->designation_id = $request['designation'];
    //     $quiz->join_date = $request['join_date'];
    //     $quiz->salary = $request['salary'];
    //     $quiz->email = $request['email'];
    //     $quiz->password = $request['password'];
    //     $quiz->address = $request['address'];


    //     // store profile photo
    //     $quizPhotoPath = 'images/staff/photos';
    //     if (!file_exists($quizPhotoPath)) {
    //         mkdir($quizPhotoPath, 0777, true);
    //     }

    //     if ($request->hasFile('photo')) {
    //         $photo = $request->file('photo');
    //         $photo_image_name = time() . mt_rand(1, 2000) . '.' . $photo->extension();
    //         $Folder = public_path($quizPhotoPath);
    //         $photo->move($Folder, $photo_image_name);
    //         $quiz->photo = $photo_image_name;
    //     }

    //     if($quiz->save())
    //     {
    //         return redirect()->route('quizzes')->with('success','Staff has been added');
    //     }

    //     return redirect()->route('quizzes')->with('error','something went wrong!!!');
    // }


    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(string $id)
    // {
    //     $quiz=Staff::findOrFail($id);
    //     $designations = Designation::where('status','1')->get();
    //     $data=compact('quizzes','designations');
    //     return view('staff.edit')->with($data);
    // }


    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request)
    // {
    //     $request->validate(
    //         [
    //             'quiz_id'=>'required',
    //             'fname'=>'required',
    //             'mname'=>'required',
    //             'lname'=>'required',
    //             'dob'=>'required',
    //             'mo'=>'required',
    //             'status'=>'required',
    //             'designation'=>'required',
    //             'join_date'=>'required',
    //             'salary'=>'required',
    //             'bank'=>'required',
    //             'ac_no'=>'required',
    //             'ifsc_no'=>'required',
    //             'licence_no'=>'required',
    //             'licence_exp_date'=>'required',
    //             'bus'=>'required',
    //             'route'=>'required',
    //             'address'=>'required',
    //         ]
    //     );


    //     $quiz=Staff::findOrFail($request->quiz_id);
    //     $quiz->fname = $request['fname'];
    //     $quiz->mname = $request['mname'];
    //     $quiz->lname = $request['lname'];
    //     $quiz->DOB = $request['dob'];
    //     $quiz->mo = $request['mo'];
    //     $quiz->status = $request['status'];
    //     $quiz->designation_id = $request['designation'];
    //     $quiz->join_date = $request['join_date'];
    //     $quiz->salary = $request['salary'];
    //     $quiz->bank = $request['bank'];
    //     $quiz->ac_no = $request['ac_no'];
    //     $quiz->ifsc_no = $request['ifsc_no'];
    //     $quiz->licence_no = $request['licence_no'];
    //     $quiz->licence_exp_date = $request['licence_exp_date'];
    //     $quiz->bus_id = $request['bus'];
    //     $quiz->route_id = $request['route'];
    //     $quiz->address = $request['address'];


    //     // store profile photo
    //     $quizPhotoPath = 'images/staff/photos';
    //     if (!file_exists($quizPhotoPath)) {
    //         mkdir($quizPhotoPath, 0777, true);
    //     }

    //     if ($request->hasFile('photo')) {
    //         $photo = $request->file('photo');
    //         $photo_image_name = time() . mt_rand(1, 2000) . '.' . $photo->extension();
    //         $Folder = public_path($quizPhotoPath);
    //         $photo->move($Folder, $photo_image_name);
    //         $quiz->photo = $photo_image_name;
    //     }


    //     if($quiz->save())
    //     {
    //         $lastId = $quiz->id;

    //          // delete Documents
    //         StaffDocument::where('quiz_id',$lastId)->delete();

    //         //insert old Documents data
    //         if($request->old_documents != null)
    //         {
    //             $p=0;
    //             foreach ($request->old_documents as $old_document) {
    //                 $doc = new StaffDocument();
    //                 $doc->quiz_id = $lastId;    
    //                 $doc->document = $old_document; 
    //                 $doc->title = $request->old_document_titles[$p];  
    //                 $doc->save();
    //                 $p++;
    //             }
    //         }

    //         // add documents
    //         $quizDocumentPath = 'images/staff/documents';
    //         if (!file_exists($quizDocumentPath)) {
    //             mkdir($quizDocumentPath, 0777, true);
    //         }
       

    //         if ($request->hasFile('documents')) {
    //             $i=0;
    //             foreach ($request->file('documents') as $document) {
    //                 $document_image_name = time() . mt_rand(1, 2000) . '.' . $document->extension();
    //                 $Folder = public_path($quizDocumentPath);
    //                 $document->move($Folder, $document_image_name);

    //                 //insert documents in database
    //                 $quizDocument = new StaffDocument();
    //                 $quizDocument->quiz_id = $lastId;    
    //                 $quizDocument->document = $document_image_name;   
    //                 $quizDocument->title = $request->document_titles[$i];
    //                 $quizDocument->save();
    //                 $i++;
    //             }
    //         }


    //         return redirect()->route('quizzes')->with('success','Staff has been updated');
    //     }

    //     return redirect()->route('quizzes')->with('error','something went wrong!!!');

    // }


    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Staff $id)
    // {
    //     $id->staffDocuments()->delete();
    //     $id->delete();
    //     return redirect()->back(); 
    // }


    // public function deleteSelected(Request $request)
    // {
    //     $selectedIds = $request->selectedIds;

    //     // Find the posts by their IDs
    //     $quizs = Staff::whereIn('id', $selectedIds)->get();

    //     // Loop through the buses and delete associated records
    //     foreach ($staffs as $staff) {
    //         $quiz->staffDocuments()->delete();
    //     }

    //     // Delete the posts
    //     Staff::whereIn('id', $selectedIds)->delete();

    //     return response()->json(['message' => 'Selected records deleted successfully']);
    // }



    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     $staff=Staff::findOrFail($id);
    //     $staffDocuments = StaffDocument::where("quiz_id",$quiz->id)->get();
    //     $documentCount = count($staffDocuments);
    //     $designations = Designation::where('status','1')->get();
    //     $buses = Bus::where('status','1')->get();
    //     $bus_routes = BusRoute::where('status','1')->get();
    //     $data=compact('quizzes','staffDocuments','documentCount','designations','buses','bus_routes');
    //     return view('staff.show')->with($data);
    // }

}
