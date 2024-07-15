<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

use DataTables;
class LeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roleId = 3;
        $role = Role::findOrFail($roleId);
        if ($request->ajax()) {

            $query = User::with('parent')
            ->role($role->name)
            ->orderBy('id', 'desc');

            if (Auth::user()->hasRole('Leader')) {
                $userId = Auth::id();
                $query->where('parent_id', $userId);
            }

            if ($request->has('leader_name') && !empty($request->leader_name)) {
                $leaderName = $request->leader_name;
                $query->where('name', 'like', "%{$leaderName}%");
            }
            $data = $query->get();


            // if (Auth::user()->hasRole('Leader')) {
            //     $user = Auth::user();
            //     $userId = $user->id;
            //     // $data = User::select('*')->role($role->name)->where('parent_id',$userId)->orderBy('id', 'desc')->get();
            //     $data = User::with('parent')
            //     ->role($role->name)
            //     ->when(Auth::user()->hasRole('Leader'), function ($query) {
            //         $userId = Auth::id();
            //         return $query->where('parent_id', $userId);
            //     })
            //     ->orderBy('id', 'desc')
            //     ->get();

                


            // }else{

            //     $data = User::with('parent')->select('*')->role($role->name)->orderBy('id', 'desc')->get();

            // }
            
 

            return Datatables::of($data)
                ->addColumn('checkbox', '<input type="checkbox" name="user_id[]" value="{{$id}}" />')
                ->addColumn('parent_name', function ($user) {
                    return $user->parent ? $user->parent->name : 'N/A';
                })
                ->addColumn('unique_id', function ($user) {
                    return $user->unique_id ? $user->unique_id : 'N/A';
                })
                ->addColumn('edit', '<button onclick="editUser({{$id}})" class="edit-btn btn btn-primary btn-circle"><i class="fas fa-edit"></i></button>')
                ->addColumn('delete', '<a href="{{route("leader-delete",["id"=>$id])}}" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a>')
                ->rawColumns(['checkbox', 'edit', 'delete'])
                ->make(true);
        }
       
        $leaders = User::select('*')->role($role->name)->orderBy('id', 'desc')->get();
        return view('leader.list',['leaders'=>$leaders]);
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'id'=>'required',
                'name'=>'required',
                'email'=>'required',
                'status'=>'required'
            ]
        );

        //new record
        if($request->id == "0"){
            $user=new User();
            $lastUser = User::orderBy('id', 'desc')->first();
            $nextId = $lastUser ? $lastUser->id + 1 : 1;
            $uniqueId = 'BB' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            $user->unique_id = $uniqueId;    
        }else{
            $user=User::findOrFail($request->id);
            
        }
        $user->name=$request['name'];
        $user->email=$request['email'];
        $user->mobile=$request['mobile'];
        $user->birthdate=$request['birthdate'];
        if($request['password']){
            $user->password=$request['password'];
        }
        $user->status=$request['status'];
        $user->parent_id=$request['parent_id'];
        $user->save();

        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('profiles', $fileName); // Store in 'storage/app/profiles' directory
    
            if ($user->profile) {
                Storage::delete($user->profile);
            }
            // Save file path to user's profile field
            $user->profile = $filePath;
            $user->save();
        }
        // $request->roles = 3;
        $user->syncRoles(3);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
     /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $id)
    {
        $id->delete();
        return redirect()->back(); 
    }


    public function deleteSelected(Request $request)
    {
        $selectedIds = $request->selectedIds;
        User::whereIn('id', $selectedIds)->delete();
        return response()->json(['status' => 'success','message' => 'Selected records deleted successfully']);
    }
}
