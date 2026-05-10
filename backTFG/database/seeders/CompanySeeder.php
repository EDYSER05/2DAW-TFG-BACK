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
        $companies = json_decode(
            file_get_contents(database_path('seeders/data/companies.json')),
            true
        );

        $ownerRoleId = Role::where('name', 'owner')->value('id');
        $managerRoleId = Role::where('name', 'manager')->value('id');
        $hrRoleId = Role::where('name', 'hr')->value('id');
        $employeeRoleId = Role::where('name', 'employee')->value('id');

        $hrDeptNames = ['Human Resources', 'Recursos Humanos', 'People'];

        foreach ($companies as $companyData) {
            // Crear primero el dueño (sin empresa todavía)
            $owner = User::create([
                'department_id' => null,
                'role_id' => $ownerRoleId,
                'name' => $companyData['owner']['name'],
                'last_name' => $companyData['owner']['last_name'],
                'email' => $companyData['owner']['email'],
                'password' => Hash::make('password'),
                'hire_date' => $companyData['owner']['hire_date'],
                'active' => true,
                'must_change_password' => false,
                'email_verified_at' => now(),
                'last_login_at' => now(),
            ]);

            // Crear empresa con owner_id ya conocido
            // El CompanyObserver enlaza automáticamente los festivos nacionales
            $company = Company::create([
                'owner_id' => $owner->id,
                'name' => $companyData['name'],
                'tax_id' => $companyData['tax_id'],
                'address' => $companyData['address'],
            ]);

            foreach ($companyData['departments'] as $deptData) {
                $isHrDept = in_array($deptData['name'], $hrDeptNames);

                // Crear departamento sin manager todavía
                $department = Department::create([
                    'company_id' => $company->id,
                    'name' => $deptData['name'],
                ]);

                // El responsable de RRHH tiene rol hr; el resto, manager
                $manager = User::create([
                    'department_id' => $department->id,
                    'role_id' => $isHrDept ? $hrRoleId : $managerRoleId,
                    'name' => $deptData['manager']['name'],
                    'last_name' => $deptData['manager']['last_name'],
                    'email' => $deptData['manager']['email'],
                    'password' => Hash::make('password'),
                    'hire_date' => $deptData['manager']['hire_date'],
                    'active' => true,
                    'must_change_password' => false,
                    'email_verified_at' => now(),
                    'last_login_at' => now(),
                ]);

                $department->update(['manager_id' => $manager->id]);

                // Empleados: en RRHH el primero tiene rol hr, el resto employee
                foreach ($deptData['employees'] as $index => $empData) {
                    User::create([
                        'department_id' => $department->id,
                        'role_id' => ($isHrDept && $index === 0) ? $hrRoleId : $employeeRoleId,
                        'name' => $empData['name'],
                        'last_name' => $empData['last_name'],
                        'email' => $empData['email'],
                        'password' => Hash::make('password'),
                        'hire_date' => $empData['hire_date'],
                        'active' => true,
                        'must_change_password' => false,
                        'email_verified_at' => now(),
                        'last_login_at' => now(),
                    ]);
                }
            }
        }
    }
}
