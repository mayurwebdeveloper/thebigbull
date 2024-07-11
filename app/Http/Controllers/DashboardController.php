<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\Investment;
use App\Models\User;
use DataTables;


class DashboardController extends Controller
{
    public function index()
    {    

        $totalInvestment = Investment::sum('investment_amount');
        $role = Role::find(3);

        // Count the users who have the role
        $totalUsersWithRole = $role->users()->count();


        return view('dashboard',compact('totalInvestment','totalUsersWithRole'));
    }
}
