<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
public function index()
{
    // 10 records per page (you can change this number)
    $accounts = DB::table('tblAccounts')->paginate(10);

    return view('Accounts.index', compact('accounts'));
}


    public function create()
    {
        return view('Accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'HeaderCode' => 'required',
            'AccountType' => 'required',
            'AccountName' => 'required',
        ]);

DB::table('tblAccounts')->insert([
    'HeaderCode'   => $request->HeaderCode,
    'AccountType'  => $request->AccountType,
    'AccountName'  => $request->AccountName,
    'Town'  => $request->Town,
    'Phone'  => $request->Phone,
    'UserId'       => session('user_id'),    // From session
    'UserName'     => session('user_name'),  // From session
    'created_at'   => now(),
    'updated_at'   => now(),
]);

        return redirect()->route('Accounts.index')->with('success', 'Account created successfully');
    }

    public function edit($id)
    {
        $account = DB::table('tblAccounts')->where('AccountId', $id)->first();
        return view('Accounts.edit', compact('account'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'HeaderCode' => 'required',
            'AccountType' => 'required',
            'AccountName' => 'required',
        ]);

        DB::table('tblAccounts')->where('AccountId', $id)->update([
            'HeaderCode' => $request->HeaderCode,
            'AccountType' => $request->AccountType,
            'AccountName'      => $request->AccountName,
                'Town'  => $request->Town,
    'Phone'  => $request->Phone,
            'UserId'       => session('user_id'),    // From session
            'UserName'     => session('user_name'),  // From session
            'updated_at' => now(),
        ]);

        return redirect()->route('Accounts.index')->with('success', 'Account updated successfully');
    }

    public function destroy($id)
    {
        DB::table('tblAccounts')->where('AccountId', $id)->delete();
        return redirect()->route('Accounts.index')->with('success', 'Account deleted successfully');
    }
}
