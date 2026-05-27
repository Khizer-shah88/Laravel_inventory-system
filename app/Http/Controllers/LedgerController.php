<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LedgerController extends Controller
{
  public function index()
    {
        $accounts = DB::table('tblAccounts')
            ->select('AccountId', 'AccountName', 'HeaderCode')
            ->orderBy('AccountName')
            ->get();

        return view('ledger.index', compact('accounts'));
    }

    public function show(Request $request)
    {
        $validated = $request->validate([
            'HeaderCode' => ['required'],
            'AccountId'  => ['required'],
            'StartDate'  => ['required','date'],
            'EndDate'    => ['required','date'],
        ]);
        



        // 👉 Opening balance = sum of all transactions before start date
$opening = DB::table('vw_Ledger')
    ->selectRaw('COALESCE(SUM(Debit),0) as totalDebit, COALESCE(SUM(Credit),0) as totalCredit')
    ->where('HeaderCode', $validated['HeaderCode'])
    ->where('AccountId',  $validated['AccountId'])
    ->where('VDate', '<', $validated['StartDate'])
    ->first();

$openingBalance = $opening->totalDebit - $opening->totalCredit;


        // 👉 Get transactions within the date range
        $rows = DB::table('vw_Ledger')
            ->select('VDate', 'Description', 'RefNo', 'Debit', 'Credit')
            ->where('HeaderCode', $validated['HeaderCode'])
            ->where('AccountId',  $validated['AccountId'])
            ->whereBetween('VDate', [$validated['StartDate'], $validated['EndDate']])
            ->orderBy('VDate')
            ->orderBy('RefNo')
            ->get();

        $ledgerData = [];
        $balance = $openingBalance;

        // 1) Add opening balance row
        $ledgerData[] = [
            'VDate'       => $validated['StartDate'],
            'Description' => 'Opening Balance',
            'RefNo'       => '',
            'Debit'       => 0,
            'Credit'      => 0,
            'Balance'     => $balance,
        ];

        // 2) Add each transaction row
        foreach ($rows as $r) {
            $debit  = (float) ($r->Debit  ?? 0);
            $credit = (float) ($r->Credit ?? 0);
            $balance += $debit - $credit;

            $ledgerData[] = [
                'VDate'       => $r->VDate,
                'Description' => $r->Description,
                'RefNo'       => $r->RefNo,
                'Debit'       => $debit,
                'Credit'      => $credit,
                'Balance'     => $balance,
            ];
        }

        // Totals
        $ledgerTotals = [
            'totalDebit'     => $rows->sum('Debit'),
            'totalCredit'    => $rows->sum('Credit'),
            'closingBalance' => $balance,
        ];
        
                return view('ledger.show', [
    'ledgerData' => $ledgerData,
    'ledgerTotals' => $ledgerTotals,
    'accountName' => $request->AccountName,
    'headerCode' => $request->HeaderCode,
    'AccountId' => $request->AccountId,
    'startDate' => $request->StartDate,
    'endDate' => $request->EndDate,
]);

        //return view('ledger.show', compact('ledgerData', 'ledgerTotals'));
    }




}
