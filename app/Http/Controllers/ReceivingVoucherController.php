<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceivingVoucherController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search');

    $vouchers = DB::table('tblReceivingVouchers')
        ->when($search, function($query, $search) {
            return $query->where('AccountName', 'like', "%{$search}%")
                         ->orWhere('AccountId', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10);

    return view('ReceivingVouchers.index', compact('vouchers'));
}

    public function create()
    {
        $accounts = DB::table('tblAccounts')->get();
        
        return view('ReceivingVouchers.create',compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'HeaderCode'  => 'required',
            'AccountId'   => 'required',
            'AccountName' => 'required|string|max:255',
            'Description' => 'nullable|string',
            'VDate'       => 'required|date',
            'Amount'      => 'required|numeric|min:0',
        ]);

        DB::table('tblReceivingVouchers')->insert([
            'HeaderCode'  => $request->HeaderCode,
            'AccountId'   => $request->AccountId,
            'AccountName' => $request->AccountName,
            'Description' => $request->Description,
            'VDate'       => $request->VDate,
            'Amount'      => $request->Amount,
            'UserId'       => session('user_id'),    // From session
            'UserName'     => session('user_name'),  // From session
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('ReceivingVouchers.index')->with('success', 'Receiving Voucher added successfully.');
    }

    public function edit($id)
    {
        $voucher = DB::table('tblReceivingVouchers')->where('id', $id)->first();
        return view('ReceivingVouchers.edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'HeaderCode'  => 'required',
            'AccountId'   => 'required',
            'AccountName' => 'required|string|max:255',
            'Description' => 'nullable|string',
            'VDate'       => 'required|date',
            'Amount'      => 'required|numeric|min:0',
        ]);

        DB::table('tblReceivingVouchers')->where('id', $id)->update([
            'HeaderCode'  => $request->HeaderCode,
            'AccountId'   => $request->AccountId,
            'AccountName' => $request->AccountName,
            'Description' => $request->Description,
            'VDate'       => $request->VDate,
            'Amount'      => $request->Amount,
            'UserId'       => session('user_id'),    // From session
            'UserName'     => session('user_name'),  // From session
            'updated_at'  => now(),
        ]);

        return redirect()->route('ReceivingVouchers.index')->with('success', 'Receiving Voucher updated successfully.');
    }

    public function destroy($id)
    {
        DB::table('tblReceivingVouchers')->where('id', $id)->delete();
        return redirect()->route('ReceivingVouchers.index')->with('success', 'Receiving Voucher deleted successfully.');
    }
}
