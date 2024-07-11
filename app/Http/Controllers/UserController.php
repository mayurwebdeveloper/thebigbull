<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::get();
        if ($request->ajax()) {
            $data = User::select('*')->orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addColumn('checkbox', '<input type="checkbox" name="user_id[]" value="{{$id}}" />')
                ->addColumn('edit', '<button onclick="editUser({{$id}})" class="edit-btn btn btn-primary btn-circle"><i class="fas fa-edit"></i></button>')
                ->addColumn('delete', '<a href="{{route("user-delete",["id"=>$id])}}" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a>')
                ->rawColumns(['checkbox', 'edit', 'delete'])
                ->make(true);
        }
        
        return view('user.list',['roles' => $roles]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            // Determine if we are updating or creating a new user
    $userId = $request->id != "0" ? $request->id : null;

        $request->validate(
            [
                'id'=>'required',
                'name'=>'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($userId)->whereNull('deleted_at')
                ],
                'status'=>'required',
                'roles'=>'required',
                'profile' => 'required|file|mimes:jpeg,png|max:2048', // Example: Allow JPEG and PNG files, max 2MB

            ]
        );

        //new record
        if($request->id == "0")
            $user=new User();
        else
            $user=User::findOrFail($request->id);

        $user->name=$request['name'];
        $user->email=$request['email'];
        

        if($request['password']){
            $user->password=$request['password'];
        }
        $user->status=$request['status'];
        $user->save();

       

        $user->syncRoles($request->roles);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user=User::findOrFail($id);
            $user_roles = $user->roles->pluck('name','name')->all();

            $responseData = [
                'user' => $user,
                'user_roles' => $user_roles
            ];

            return response()->json($responseData, 200); // Return a JSON response with a 200 status code
        } catch (\Exception $e) {
            // Handle exceptions, for example, if the bus route is not found
            $errorMessage = 'User not found.';
            return response()->json(['error' => $errorMessage], 404); // Return a JSON response with a 404 status code
        }
    }


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

    public function getChild($parent_id){
        $users = User::with('investments')->where('parent_id',$parent_id)
        ->orderBy('id', 'asc') // Order parent users by ID ascending
        ->with([
            'children' => function($query) {
                $query->orderBy('id', 'asc') // Order child users by ID ascending
                ->with('investments') 
                ->with([
                        'children' => function($query) {
                            $query->orderBy('id', 'asc') // Order children's children by ID ascending
                            ->with('investments')    
                            ->limit(2); // Limit to 2
                        }
                    ]);
            }
        ])
        ->get();
    }

    public function showchild(){
        
        $users = User::with('investments')->whereNull('parent_id')
        ->orderBy('id', 'asc') // Order parent users by ID ascending
        ->with([
            'children' => function($query) {
                $query->orderBy('id', 'asc') // Order child users by ID ascending
                ->with('investments') 
                ->with([
                        'children' => function($query) {
                            $query->orderBy('id', 'asc') // Order children's children by ID ascending
                            ->with('investments')    
                            ->limit(2); // Limit to 2
                        }
                    ]);
            }
        ])
        ->get();

        foreach($users as $user){

        }

        // echo "<pre>";
        // print_r($users);
        // exit;
    }

    public function calculate()
    {
        // Load top-level users with their investments and nested children up to 3 levels
        // $users = User::with('investments', 'children.children.investments')->where('id','!=','1')->whereNull('parent_id')->get();
        $users = User::with('investments')->where('id','!=','1')->whereNull('parent_id')
        ->orderBy('id', 'asc') // Order parent users by ID ascending
        ->with([
            'children' => function($query) {
                $query->orderBy('id', 'asc') // Order child users by ID ascending
                ->with('investments') 
                ->with([
                        'children' => function($query) {
                            $query->orderBy('id', 'asc') // Order children's children by ID ascending
                            ->with('investments')    
                            ->limit(2); // Limit to 2
                        }
                    ]);
            }
        ])
        ->get();
        // dd($users);
        // Initialize an array to store commissions
        $commissions = [];

        // Calculate commissions for each top-level user
        foreach ($users as $user) {
            $this->calculateCommissions($user, $commissions);
        }

        // $dataofcollection = 
        foreach($commissions as $key => $val){

        }

        // echo "<pre>";
        // print_r($commissions);
        // exit;

        // Return the commissions for display (or other processing)
        // return view('commissions', compact('commissions'));
    }

    private function calculateCommissions($user, &$commissions, $level = 1)
    {
//         $user = User::with('investments')->find(1);
// dd($user);

        // Define commission rates based on levels
        $commissionRate = [
            1 => 5, // Level 1 commission
            2 => 1, // Level 2 commission
            3 => 0.5 // Level 3 commission
        ];

        // dd($user->investment);
        // Calculate commission for the current user's own investments
        $userInvestment = optional($user->investments)->investment_amount ?? 0;
        $commissions[$user->id]['total'] = isset($commissions[$user->id]['total']) ? $commissions[$user->id]['total'] : 0;
        $commissions[$user->id]['total'] += $userInvestment * ($commissionRate[$level] ?? 0);

        // Process each child to calculate and propagate commissions
        foreach ($user->children as $child) {
            $this->calculateCommissions($child, $commissions, $level + 1);

            // Add the child's commission to the current user's commission
            if (isset($commissions[$child->id]['total'])) {
                $commissions[$user->id]['total'] += $commissions[$child->id]['total'] * ($commissionRate[$level + 1] ?? 0);
            }
        }
    }
}
