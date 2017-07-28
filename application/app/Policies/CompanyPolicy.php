<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before() {
        if (isSuperAdmin()) {
            return false;
        }
    }
}
