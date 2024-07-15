<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Investment;
use App\Models\Commission;
use Carbon\Carbon;
class InsertComissionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:insert';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::with('investments')->where('id','!=','1') //->whereNull('parent_id')
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
        $users->each(function ($user) {
            $total_investment = 0;
            $totalDaysInMonth = Carbon::now()->daysInMonth;

            // Check if investments relation is loaded and not null
            // $total_investment = $user->investments ? $user->investments->sum('investment_amount') : 0;
            // print_r(['user_id', $user->id]);
            
            $totalInvestments = Investment::where('user_id', $user->id)->sum('investment_amount');
            $total_investment = $totalInvestments ? $totalInvestments : 0;
         

            $child_total_investment = 0;
            $grandchild_total_investment = 0;
        
            $user->children->each(function ($child) use (&$child_total_investment, &$grandchild_total_investment) {
                
                // Check if investments relation is loaded and not null
                // $child_total_investment += $child->investments ? $child->investments->sum('investment_amount') : 0;
                $child_total_investmentss = Investment::where('user_id', $child->id)->sum('investment_amount');
                $child_total_investment += $child_total_investmentss ? $child_total_investmentss : 0;



                $child->children->each(function ($grandchild) use (&$grandchild_total_investment) {
                    // Check if investments relation is loaded and not null
                    // $grandchild_total_investment += $grandchild->investments ? $grandchild->investments->sum('investment_amount') : 0;
                    $grandchild_total_investmentss = Investment::where('user_id', $grandchild->id)->sum('investment_amount');
                    $grandchild_total_investment += $grandchild_total_investmentss ? $grandchild_total_investmentss : 0;

                });
            });
        

            $own_comission_percent = (5 / $totalDaysInMonth);
            $child_comission_percent = (1.5 / $totalDaysInMonth);
            $grand_comission_percent = (0.5 / $totalDaysInMonth);
            
            $own_comission = (($total_investment * $own_comission_percent) / 100);
            $child_comission = (($child_total_investment * $child_comission_percent) / 100);
            $grand_comission = (($grandchild_total_investment * $grand_comission_percent) / 100);

            $total_comission_user = $own_comission + $child_comission + $grand_comission;

            $comission=new Commission();
            $comission->user_id = $user->id;
            $comission->commission_amount = $total_comission_user; 
            $comission->before_commission_added = 0;
            $comission->after_commission_added = 0;
            $comission->commission_date = date('Y-m-d');
            $comission->commission_from = 1;
            $comission->save();


            // echo $user->id;
            // echo "<br>";
            // echo "Own Investment: " . $total_investment;
            // echo "<br>";
            // echo "Own Comission: " . $own_comission;
            
            // echo "<br>";
            // echo "Child Investment: " . $child_total_investment;
            // echo "<br>";
            // echo "child Comission: " . $child_comission;
            // echo "<br>";
            // echo "Grandchild Investment: " . $grandchild_total_investment;
            // echo "<br>";
            // echo "grand Comission: " . $grand_comission;
            // echo "<br>";
            // echo "<br>";
            // echo "<br>";
        });
        //
    }

   
}
