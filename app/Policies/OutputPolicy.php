<?php

namespace App\Policies;

use App\Models\CashClosing;
use App\Models\Output;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OutputPolicy {

    use HandlesAuthorization;

    public function __construct() {
        
    }

    public function isAccounted(User $user, Output $output){

        $lastRecord = CashClosing::latest('id')->where('terminal_id', $output->terminal->id)->first();

        if (!$lastRecord) return true;

         return $output->created_at->gt($lastRecord->created_at);

    }

}
