<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commission;
use Illuminate\Support\Facades\DB;

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

        
        // return view('commissions.index', compact('monthlyCommissions'));
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

    return view('commissions.index', compact('monthlyCommissions', 'year', 'month', 'username'));


        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function userwisetotal(Request $request)
    {

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
