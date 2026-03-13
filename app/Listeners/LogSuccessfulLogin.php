<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        DB::table('tbl_last_login')->insert([
            'userId' => $user->userId,
            'sessionData' => json_encode([
                'role' => $user->roleId,
                'name' => $user->name,
                'designation' => $user->designation,
                'isAdmin' => $user->isAdmin,
            ]),
            'machineIp' => Request::ip(),
            'userAgent' => Request::header('User-Agent'),
            'agentString' => Request::header('User-Agent'),
            'platform' => PHP_OS,
            'createdDtm' => now(),
        ]);
    }
}
