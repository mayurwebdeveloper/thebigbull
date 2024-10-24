<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\Investment;
use App\Models\User;

use DataTables;
class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roleId = 3;
        $role = Role::findOrFail($roleId);
        if ($request->ajax()) {

            if (Auth::user()->hasRole('Leader')) {
                $user = Auth::user();
                $userId = $user->id;
                $data = User::select('*')->role($role->name)->where('parent_id',$userId)->orderBy('id', 'desc')->get();

            }else{

                $data = User::select('*')->role($role->name)->orderBy('id', 'desc')->get();

            }
            
            $loggedInUser = auth()->user();

            if($loggedInUser->hasRole('Leader')){
                // $data = Investment::select('*')->with('user')->orderBy('id', 'desc')->get();
                        // $data = Investment::select('*')
                        // ->with('user') // Load related user data
                        // ->when($loggedInUser->role_id == 3, function ($query) use ($loggedInUser) {
                        //     // If logged-in user is a leader, filter investments by their child users
                        //     return $query->whereHas('user', function ($q) use ($loggedInUser) {
                        //         $q->where('parent_id', $loggedInUser->id);
                        //     });
                        // })
                        // ->orderBy('id', 'desc')
                        // ->get();
                        // $data = Investment::select('investments.*')
                        //         ->join('users', 'users.id', '=', 'investments.user_id')
                        //         ->where('users.parent_id', '=', $loggedInUser->id) // Filter by leader's child users
                        //         ->orderBy('investments.id', 'desc')
                        //         ->get();
                        $leader = auth()->user();

                        // Recursive query to get all children and their descendants
                        $descendantUserIds = User::where('parent_id', $leader->id)
                            ->orWhereIn('parent_id', function ($query) use ($leader) {
                                $query->select('id')->from('users')->where('parent_id', $leader->id);
                            })
                            ->pluck('id');

                        // Include the leader's own investments if needed
                        $descendantUserIds[] = $leader->id;

                        // Query to get investment data for the Leader, children, and grandchildren
                        $data = Investment::whereIn('user_id', $descendantUserIds)
                            ->orderBy('id', 'desc')
                            ->get();



            }else{
                $data = Investment::select('*')->with('user')->orderBy('id', 'desc')->get();
            }
           
            
          
            return Datatables::of($data)
                ->addColumn('checkbox', '<input type="checkbox" name="investment_id[]" value="{{$id}}" />')
                ->addColumn('user_name', function($investment) {
                    return $investment->user ? $investment->user->name : 'N/A';
                })
                ->addColumn('investment_amount', function($investment) {
                    return $investment->investment_amount; // Adjust field name if necessary
                })
                ->addColumn('created_at', function($investment) {
                    return $investment->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('edit', '<button onclick="editUser({{$id}})" class="edit-btn btn btn-primary btn-circle"><i class="fas fa-edit"></i></button>')
                ->addColumn('delete', '<a href="{{route("investment-delete",["id"=>$id])}}" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a>')
                ->rawColumns(['checkbox', 'edit', 'delete'])
                ->make(true);
        }
       
        $leaders = User::select('*')->role($role->name)->orderBy('id', 'desc')->get();
        return view('investment.list',['leaders'=>$leaders]);
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
                'user_id'=>'required',
                'investment_amount'=>'required'
            ]
        );

        //new record
        if($request->id == "0")
            $Investment=new Investment();
        else
            $Investment=Investment::findOrFail($request->id);

        $Investment->user_id=$request['user_id'];
        $Investment->investment_amount=$request['investment_amount'];
      
        $Investment->save();
        // $request->roles = 3;
        // $user->syncRoles(3);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $investment = Investment::findOrfail($id);
        try {
            $investment = Investment::findOrfail($id);
            

            $responseData = [
                'investment_id' => $investment->id,
                'user_id' => $investment->user_id,
                'investment_amount' => $investment->investment_amount
            ];

            return response()->json($responseData, 200); // Return a JSON response with a 200 status code
        } catch (\Exception $e) {
            // Handle exceptions, for example, if the bus route is not found
            $errorMessage = 'User not found.';
            return response()->json(['error' => $errorMessage], 404); // Return a JSON response with a 404 status code
        }
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
    public function destroy(Investment $id)
    {
        $id->delete();
        return redirect()->back(); 
    }


    public function deleteSelected(Request $request)
    {
        $selectedIds = $request->selectedIds;
        Investment::whereIn('id', $selectedIds)->delete();
        return response()->json(['status' => 'success','message' => 'Selected records deleted successfully']);
    }
}
