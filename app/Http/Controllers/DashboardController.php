<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DashboardController extends Controller
{
    

public function index()
    {
        // Only show dashboard if user logged in
        if (!session('user_id')) {
            return redirect('/login');
        }

        // Fetch currently logged-in users
        $onlineUsers = DB::table('tbl_user_logins')
            ->join('users', 'users.id', '=', 'tbl_user_logins.user_id')
            ->where('is_logged_in', true)
            ->select('users.username', 'tbl_user_logins.ip_address', 'tbl_user_logins.last_activity')
            ->get();

        return view('index', compact('onlineUsers'));
    }

}
