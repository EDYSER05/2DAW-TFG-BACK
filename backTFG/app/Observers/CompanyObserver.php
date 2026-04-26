<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\Holiday;

class CompanyObserver
{
    public function created(Company $company): void
    {
        $nationalHolidayIds = Holiday::pluck('id');

        if ($nationalHolidayIds->isNotEmpty()) {
            $company->holidays()->attach($nationalHolidayIds);
        }
    }
}
