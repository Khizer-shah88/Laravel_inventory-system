<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentVoucherController extends Controller
{
    
    public function index(Request $request)
{
    $search = $request->input('search');

    $vouchers = DB::table('tblPaymentVouchers')
        ->when($search, function($query, $search) {
            return $query->where('AccountName', 'like', "%{$search}%")
                         ->orWhere('AccountId', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10);

    return view('PaymentVouchers.index', compact('vouchers'));
}



    public function create()
    
    {
        $accounts = DB::table('tblAccounts')->get();
        return view('PaymentVouchers.create', compact('accounts'));
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

        DB::table('tblPaymentVouchers')->insert([
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

        return redirect()->route('PaymentVouchers.index')->with('success', 'Payment Voucher added successfully.');
    }

    public function edit($id)
    {
        $voucher = DB::table('tblPaymentVouchers')->where('id', $id)->first();
        return view('PaymentVouchers.edit', compact('voucher'));
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

        DB::table('tblPaymentVouchers')->where('id', $id)->update([
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

        return redirect()->route('PaymentVouchers.index')->with('success', 'Payment Voucher updated successfully.');
    }

    public function destroy($id)
    {
        DB::table('tblPaymentVouchers')->where('id', $id)->delete();
        return redirect()->route('PaymentVouchers.index')->with('success', 'Payment Voucher deleted successfully.');
    }
}