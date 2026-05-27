<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class Customers extends Controller
{
    
public function getAccounts(Request $request) {
    if ($request->filled('AccountName')) {
        $term = $request->AccountName;

        return \DB::table('tblAccounts')
            ->select('HeaderCode', 'AccountId', 'AccountName', 'Town')
            ->where('AccountName', 'like', '%' . $term . '%')
            ->orderBy('AccountName')
            ->limit(10)
            ->get();
    }
    return [];
}


public function showLoginForm()
{
    return view('login'); // Make sure you have a resources/views/login.blade.php
}


public function login(Request $req)
{
    $req->validate([
        'user_name' => 'required',
        'password' => 'required',
    ]);

    // Find user by username and password (plain text)
    $user = DB::table('users')
        ->where('username', $req->user_name)
        ->where('password', $req->password)
        ->first();

    if ($user) {

        // Store user info in session
        session()->put('user_name', $user->username);
        session()->put('user_id', $user->id);
        session()->put('company_name', $user->company_name);
        session()->put('phone', $user->phone);
        session()->put('address', $user->address);

        // 🧠 Track login info globally
        DB::table('tbl_user_logins')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'ip_address'   => $req->ip(),
                'user_agent'   => $req->header('User-Agent'),
                'last_activity'=> now(),
                'is_logged_in' => true,
            ]
        );

        return redirect()->route('home');

    } else {
        return redirect('/login')->withErrors(['Invalid credentials']);
    }
}


public function logout(Request $request)
{
    $userId = session('user_id');

    if ($userId) {
        DB::table('tbl_user_logins')
            ->where('user_id', $userId)
            ->update(['is_logged_in' => false, 'last_activity' => now()]);
    }

    session()->flush();
    return redirect('/login');
}



}
