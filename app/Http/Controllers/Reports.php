<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Reports extends Controller
{


public function balanceSheet()
{
    $balances = DB::table('tblJournalSub as js')
        ->join('tblAccounts as a', 'js.AccountID', '=', 'a.AccountID')
        ->select(
            'a.HeaderCode',
            'a.AccountType',
            DB::raw('SUM(js.Debit - js.Credit) as Balance')
        )
        ->groupBy('a.HeaderCode', 'a.AccountType')
        ->orderBy('a.HeaderCode')
        ->get();

    // Split by sign
    $positiveAccounts = $balances->filter(fn($row) => $row->Balance >= 0);
    $negativeAccounts = $balances->filter(fn($row) => $row->Balance < 0);

    // Totals
    $totalPositive = $positiveAccounts->sum('Balance');
    $totalNegative = $negativeAccounts->sum('Balance');

    return view('Reports.balance_sheet', compact(
        'positiveAccounts', 'negativeAccounts',
        'totalPositive', 'totalNegative'
    ));
}


public function expense()
    {
        $data = DB::table('tblCashBookSub')
            ->select('AccountName', DB::raw('SUM(Debit - Credit) as Balance'))
            ->where('HeaderCode', 301)
            ->groupBy('AccountName')
            ->get();

    $grandTotal = $data->sum('Balance'); // Collection sum

        return view('Reports.expense', compact('data','grandTotal'));
    }
    
public function bank()
    {
        $data = DB::table('tblCashBookSub')
            ->select('AccountName', DB::raw('SUM(Debit - Credit) as Balance'))
            ->where('HeaderCode', 701)
            ->groupBy('AccountName')
            ->get();
            
            $grandTotal = $data->sum('Balance'); // Collection sum

        return view('Reports.bank', compact('data','grandTotal'));
    }    
    
public function personal_loan()
    {
        $data = DB::table('tblCashBookSub')
            ->select('AccountName', DB::raw('SUM(Debit - Credit) as Balance'))
            ->where('HeaderCode', 702)
            ->groupBy('AccountName')
            ->get();
            
            $grandTotal = $data->sum('Balance'); // Collection sum

        return view('Reports.personal_loan', compact('data','grandTotal'));
    }        

public function company_claim()
    {
        $data = DB::table('tblCashBookSub')
            ->select('AccountName', DB::raw('SUM(Debit - Credit) as Balance'))
            ->where('HeaderCode', 901)
            ->groupBy('AccountName')
            ->get();
            
            $grandTotal = $data->sum('Balance'); // Collection sum

        return view('Reports.company_claim', compact('data','grandTotal'));
    }        


public function staff_loan()
    {
        $data = DB::table('tblCashBookSub')
            ->select('AccountName', DB::raw('SUM(Debit - Credit) as Balance'))
            ->where('HeaderCode', 703)
            ->groupBy('AccountName')
            ->get();
            
            $grandTotal = $data->sum('Balance'); // Collection sum

        return view('Reports.staff_loan', compact('data','grandTotal'));
    }       
    
public function customer_receivables()
    {
$union = DB::table('tblCashBookSub as cbs')
    ->select('cbs.AccountId', 'cbs.AccountName', DB::raw('cbs.Debit - cbs.Credit as Amount'))
    ->where('cbs.HeaderCode', 101)
    ->unionAll(
        DB::table('tblJournalSub as js')
            ->select('js.AccountId', 'js.AccountName', DB::raw('js.Debit - js.Credit as Amount'))
            ->where('js.HeaderCode', 101)
    );

$data = DB::query()
    ->fromSub($union, 'u')
    ->join('tblAccounts as a', 'u.AccountId', '=', 'a.AccountId')
    ->select('u.AccountName', 'a.Town', DB::raw('SUM(u.Amount) as Balance'))
    ->groupBy('u.AccountName', 'a.Town')
    ->orderBy('a.Town')   // ✅ Order by Town
    ->get();



            
            $grandTotal = $data->sum('Balance'); // Collection sum

        return view('Reports.customer_receivables', compact('data','grandTotal'));
    }    
    
public function company_payables()
    {
$union = DB::table('tblCashBookSub as cbs')
    ->select('cbs.AccountId', 'cbs.AccountName', DB::raw('cbs.Debit - cbs.Credit as Amount'))
    ->where('cbs.HeaderCode', 201)
    ->unionAll(
        DB::table('tblJournalSub as js')
            ->select('js.AccountId', 'js.AccountName', DB::raw('js.Debit - js.Credit as Amount'))
            ->where('js.HeaderCode', 201)
    );

$data = DB::query()
    ->fromSub($union, 'u')
    ->join('tblAccounts as a', 'u.AccountId', '=', 'a.AccountId')
    ->select('u.AccountName', 'a.Town', DB::raw('SUM(u.Amount) as Balance'))
    ->groupBy('u.AccountName', 'a.Town')
    ->orderBy('a.Town')   // ✅ Order by Town
    ->get();



            
            $grandTotal = $data->sum('Balance'); // Collection sum

        return view('Reports.customer_receivables', compact('data','grandTotal'));
    }        


}