<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commission;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Investment;

class ComissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $username = $request->input('username');
        // $monthlyCommissions = Commission::select(
        //     'users.name as username', // Assuming 'name' is the column for username in the users table
        //     'commissions.user_id',
        //     DB::raw('YEAR(commissions.commission_date) as year'),
        //     DB::raw('MONTH(commissions.commission_date) as month'),
        //     DB::raw('SUM(commissions.commission_amount) as total_commission')
        // )
        // ->leftJoin('users', 'users.id', '=', 'commissions.user_id')
        // ->groupBy('users.name', 'commissions.user_id', 'year', 'month')
        // ->orderBy('year', 'desc')
        // ->orderBy('month', 'desc')
        // ->get();
        $loggedInUser = auth()->user();

        if($loggedInUser->hasRole('Leader')){

            $query = Commission::select(
                'users.name as username',
                'commissions.user_id',
                DB::raw('YEAR(commissions.commission_date) as year'),
                DB::raw('MONTH(commissions.commission_date) as month'),
                DB::raw('SUM(commissions.commission_amount) as total_commission')
            )
            ->leftJoin('users', 'users.id', '=', 'commissions.user_id')
            ->where(function($q) use ($loggedInUser) {
                $q->where('users.parent_id', '=', $loggedInUser->id) // Leader's child users
                  ->orWhere('users.id', '=', $loggedInUser->id); // Include the logged-in user
            })
            ->groupBy('users.name', 'commissions.user_id', 'year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc');

            // Apply filters if present
            if ($year) {
                $query->having('year', '=', $year);
            }
            if ($month) {
                $query->having('month', '=', $month);
            }
            if ($username) {
                $query->having('username', 'like', '%' . $username . '%');
            }

            // Get the filtered results
            $monthlyCommissions = $query->get();

            // $query = Commission::select(
            //     'users.name as username',
            //     'commissions.user_id',
            //     DB::raw('YEAR(commissions.commission_date) as year'),
            //     DB::raw('MONTH(commissions.commission_date) as month'),
            //     DB::raw('SUM(commissions.commission_amount) as total_commission')
            // )
            // ->leftJoin('users', 'users.id', '=', 'commissions.user_id')
            // ->where('users.parent_id', '=', $loggedInUser->id) // Restrict to leader's child users
            // ->groupBy('users.name', 'commissions.user_id', 'year', 'month')
            // ->orderBy('year', 'desc')
            // ->orderBy('month', 'desc');
    
            // // Apply filters if present
            // if ($year) {
            //     $query->having('year', '=', $year);
            // }
            // if ($month) {
            //     $query->having('month', '=', $month);
            // }
            // if ($username) {
            //     $query->having('username', 'like', '%' . $username . '%');
            // }
    
            // // Get the filtered results
            // $monthlyCommissions = $query->get();

        }else{
            $query = Commission::select(
                'users.name as username',
                'commissions.user_id',
                DB::raw('YEAR(commissions.commission_date) as year'),
                DB::raw('MONTH(commissions.commission_date) as month'),
                DB::raw('SUM(commissions.commission_amount) as total_commission')
            )
            ->leftJoin('users', 'users.id', '=', 'commissions.user_id')
            ->groupBy('users.name', 'commissions.user_id', 'year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc');
    
            // Apply filters if present
            if ($year) {
                $query->having('year', '=', $year);
            }
            if ($month) {
                $query->having('month', '=', $month);
            }
            if ($username) {
                $query->having('username', 'like', '%' . $username . '%');
            }
    
            // Get the filtered results
            $monthlyCommissions = $query->get();

        }
        // return view('commissions.index', compact('monthlyCommissions'));
        

    return view('commissions.index', compact('monthlyCommissions', 'year', 'month', 'username'));


        //
    }

    public function getTotalCommissionUser($user_id){
        
        $query = Commission::select(
            // 'users.name as username',
            'commissions.user_id',
            DB::raw('SUM(commissions.commission_amount) as total_commission')
        )
        // ->leftJoin('users', 'users.id', '=', 'commissions.user_id')
        ->where('commissions.user_id', $user_id)
        ->groupBy('commissions.user_id')
        ->orderBy('total_commission', 'desc');
        $totalCommissionsarr = $query->get();
        $total_comission_arr = $totalCommissionsarr->toArray();
        return $total_comission_arr[0]['total_commission'];
    }

    public function subleader($id){

        $subLeaders = User::with('investments')
                        ->where('id', '=', $id) // Replace with the parent user's id
                        ->orderBy('id', 'asc') // Order parent users by ID ascending
                        ->with([
                            'children' => function ($query) {
                                $query->orderBy('id', 'asc') // Order child users by ID ascending
                                    ->with('investments')
                                    ->with([
                                        'children' => function ($query) {
                                            $query->orderBy('id', 'asc') // Order children's children by ID ascending
                                                ->with('investments');
                                                // ->limit(2); // Limit to 2 children
                                        }
                                    ]);
                            }
                        ])
                        ->get();

        // Calculate total investments
        $subLeaders->each(function ($user) {

            $user->total_investment = Investment::where('user_id', $user->id)->sum('investment_amount');

            // total comission get

            $user->total_commission = $this->getTotalCommissionUser($user->id);

            // echo "<pre>";
            // print_r($totalCommissions->toArray());
            // exit;
            // $user->total_investment = $user->investments ? $user->investments->sum('investment_amount') : 0; // Check if investments are not null

            // Calculate total investment for children
            $user->children->each(function ($child) {
                // $child->total_investment = $child->investments ? $child->investments->sum('investment_amount') : 0;
                $child->total_investment = Investment::where('user_id', $child->id)->sum('investment_amount');
                $child->total_commission = $this->getTotalCommissionUser($child->id);
                // Calculate total investment for grandchildren
                $child->children->each(function ($grandChild) {
                    // $grandChild->total_investment = $grandChild->investments ? $grandChild->investments->sum('investment_amount') : 0;
                    $grandChild->total_investment = Investment::where('user_id', $grandChild->id)->sum('investment_amount');
                    $grandChild->total_commission = $this->getTotalCommissionUser($grandChild->id);

                });
            });
        });


        $subLeadersArray = $subLeaders->toArray();

        return view('commissions.subleader',compact('subLeadersArray'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function userwisetotal(Request $request)
    {

        $loggedInUser = auth()->user();

        if($loggedInUser->hasRole('Leader')){
            $username = $request->input('username');

            $query = Commission::select(
                'users.name as username',
                'commissions.user_id',
                DB::raw('SUM(commissions.commission_amount) as total_commission')
            )
            ->leftJoin('users', 'users.id', '=', 'commissions.user_id')
            // ->where('users.parent_id', '=', $loggedInUser->id) // Restrict to leader's child users
            ->where(function($q) use ($loggedInUser) {
                $q->where('users.parent_id', '=', $loggedInUser->id) // Leader's child users
                  ->orWhere('users.id', '=', $loggedInUser->id); // Include the logged-in user
            })
            ->groupBy('users.name', 'commissions.user_id')
            ->orderBy('total_commission', 'desc');
    
            // Apply filters if present
            if ($username) {
                $query->having('username', 'like', '%' . $username . '%');
            }
    
            // Get the filtered results
            $totalCommissions = $query->get();

        }else{
            $username = $request->input('username');

            $query = Commission::select(
                'users.name as username',
                'commissions.user_id',
                DB::raw('SUM(commissions.commission_amount) as total_commission')
            )
            ->leftJoin('users', 'users.id', '=', 'commissions.user_id')
            ->groupBy('users.name', 'commissions.user_id')
            ->orderBy('total_commission', 'desc');
    
            // Apply filters if present
            if ($username) {
                $query->having('username', 'like', '%' . $username . '%');
            }
    
            // Get the filtered results
            $totalCommissions = $query->get();
        }

      
        return view('commissions.userwisetotal', compact('totalCommissions'));

        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
