<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Step 0: Create a permissions    
        $this->call(PermissionSeeder::class);


        // Step 1: Create a user
        User::query()->delete();

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'status' => 1,
        ]);

        // Step 2: Create a role
        $role = Role::create(['name' => 'admin']);

        // Step 3: Grant all permissions to the role
        $permissions = Permission::all();
        $role->syncPermissions($permissions);

        // Step 4: Assign the role to the user
        $user->assignRole($role);

        // Step 5 import state district city tables
        $path = database_path('sql/indian_state_district_city.sql');
        
        // Check if the file exists
        if (File::exists($path)) {
            // Read the SQL file content
            $sql = File::get($path);

            // Parse the SQL file to find table names and create DROP TABLE statements
            preg_match_all('/CREATE TABLE `([^`]+)`/', $sql, $matches);
            $tableNames = $matches[1];
            
            $dropTablesSql = '';
            foreach ($tableNames as $table) {
                $dropTablesSql .= "DROP TABLE IF EXISTS `$table`;\n";
            }

            // Combine DROP TABLE statements with the original SQL content
            $finalSql = $dropTablesSql . $sql;

            // Execute the combined SQL content
            DB::unprepared($finalSql);
        }
        
    }
}
