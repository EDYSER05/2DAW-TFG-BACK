<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies   = json_decode(
            file_get_contents(database_path('seeders/data/companies.json')),
            true
        );

        $managerRoleId  = Role::where('name', 'manager')->value('id');
        $employeeRoleId = Role::where('name', 'employee')->value('id');

        foreach ($companies as $companyData) {
            // Crear empresa — el CompanyObserver enlaza automáticamente los festivos nacionales
            $company = Company::create([
                'name'    => $companyData['name'],
                'tax_id'  => $companyData['tax_id'],
                'address' => $companyData['address'],
            ]);

            foreach ($companyData['departments'] as $deptData) {
                // Crear departamento sin manager todavía
                $department = Department::create([
                    'company_id' => $company->id,
                    'name'       => $deptData['name'],
                ]);

                // Crear manager del departamento
                $manager = User::create([
                    'department_id'     => $department->id,
                    'role_id'           => $managerRoleId,
                    'name'              => $deptData['manager']['name'],
                    'last_name'         => $deptData['manager']['last_name'],
                    'email'             => $deptData['manager']['email'],
                    'password'          => Hash::make('password'),
                    'hire_date'         => $deptData['manager']['hire_date'],
                    'active'            => true,
                    'email_verified_at' => now(),
                    'last_login_at'     => now(),
                ]);

                // Asignar manager al departamento
                $department->update(['manager_id' => $manager->id]);

                // Crear empleados del departamento
                foreach ($deptData['employees'] as $empData) {
                    User::create([
                        'department_id'     => $department->id,
                        'role_id'           => $employeeRoleId,
                        'name'              => $empData['name'],
                        'last_name'         => $empData['last_name'],
                        'email'             => $empData['email'],
                        'password'          => Hash::make('password'),
                        'hire_date'         => $empData['hire_date'],
                        'active'            => true,
                        'email_verified_at' => now(),
                        'last_login_at'     => now(),
                    ]);
                }
            }
        }
    }
}
