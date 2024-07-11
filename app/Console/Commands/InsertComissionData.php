<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Commission;
use App\Models\User;
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

            $comission=new Commission();
            $comission->user_id = $key;
            $comission->commission_amount = $val['total']; 
            $comission->before_commission_added = 0;
            $comission->after_commission_added = 0;
            $comission->commission_date = date('Y-m-d');
            $comission->commission_from = 1;
            $comission->save();

        }
        //
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
